<?php

namespace App\Services;

use App\Models\BillableItem;
use App\Models\CustomerService;
use App\Models\ServiceCancellationRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CancellationService
{
    public function preview(CustomerService $customerService): array
    {
        $requestedAt = now()->startOfDay();
        $withinCoolingOff = $customerService->start_date->copy()->startOfDay()->diffInDays($requestedAt, false) <= 7;

        if ($withinCoolingOff) {
            return [
                'policy_type' => 'cooling_off',
                'requested_at' => $requestedAt,
                'effective_at' => $requestedAt,
                'estimated_usage_cost' => 0.0,
                'is_free' => true,
            ];
        }

        $periodStart = ($customerService->current_period_start ?? $customerService->start_date)->copy()->startOfDay();
        $periodEnd = ($customerService->current_period_end ?? $this->fallbackPeriodEnd($customerService, $periodStart))->copy()->startOfDay();
        $totalDays = max(1, $periodStart->diffInDays($periodEnd));
        $usedDays = min($totalDays, max(0, $periodStart->diffInDays($requestedAt)));
        $ratio = $usedDays / $totalDays;

        return [
            'policy_type' => 'notice_period',
            'requested_at' => $requestedAt,
            'effective_at' => $requestedAt->copy()->addMonthNoOverflow(),
            'estimated_usage_cost' => round((float) $customerService->price * $ratio, 2),
            'is_free' => false,
        ];
    }

    public function request(User $user, CustomerService $customerService, ?string $reason, PortalTicketService $ticketService): ServiceCancellationRequest
    {
        if ($customerService->user_id !== $user->id || ! $customerService->isActive()) {
            throw ValidationException::withMessages(['service' => 'Deze dienst kan niet worden opgezegd.']);
        }

        if ($customerService->cancellationRequests()->where('status', 'pending')->exists()) {
            throw ValidationException::withMessages(['service' => 'Er loopt al een opzegverzoek voor deze dienst.']);
        }

        $preview = $this->preview($customerService);

        return DB::transaction(function () use ($user, $customerService, $reason, $ticketService, $preview) {
            $ticket = $ticketService->create($user, [
                'title' => 'Opzegging '.$customerService->service->title,
                'description' => 'Ik wil deze dienst opzeggen per '.$preview['effective_at']->format('d-m-Y').".\n\n".($reason ?: 'Geen aanvullende reden opgegeven.'),
                'priority' => 'medium',
                'category' => 'billing',
                'request_type' => 'dienst_opzeggen',
                'request_details' => ['service:'.$customerService->id, 'beleid:'.$preview['policy_type']],
                'page' => 'Mijn diensten',
            ]);

            $request = $customerService->cancellationRequests()->create([
                'user_id' => $user->id,
                'ticket_id' => $ticket->id,
                'status' => 'pending',
                'policy_type' => $preview['policy_type'],
                'requested_at' => $preview['requested_at'],
                'effective_at' => $preview['effective_at'],
                'original_end_date' => $customerService->end_date,
                'estimated_usage_cost' => $preview['estimated_usage_cost'],
                'reason' => $reason,
            ]);

            $customerService->update([
                'cancel_at_period_end' => true,
                'cancelled_at' => now(),
                'auto_renew' => false,
                'end_date' => $preview['effective_at'],
            ]);

            return $request;
        });
    }

    public function approve(ServiceCancellationRequest $cancellation, array $attributes): void
    {
        DB::transaction(function () use ($cancellation, $attributes) {
            $cancellation->update([
                'status' => 'approved',
                'effective_at' => $attributes['effective_at'] ?? $cancellation->effective_at,
                'estimated_usage_cost' => $attributes['estimated_usage_cost'] ?? $cancellation->estimated_usage_cost,
                'admin_notes' => $attributes['admin_notes'] ?? null,
            ]);
            $cancellation->customerService->update(['end_date' => $cancellation->fresh()->effective_at]);

            if ($cancellation->fresh()->effective_at->isPast()) {
                $cancellation->customerService->update([
                    'status' => 'cancelled',
                    'suspension_reason' => 'cancellation',
                    'auto_renew' => false,
                ]);
                $cancellation->update(['status' => 'completed']);
                $this->suspendExternally($cancellation->customerService);
                $this->notifySuspended($cancellation->customerService->fresh());
            }

            $this->createRefundTransaction($cancellation->customerService, $cancellation->fresh()->effective_at);

            if ((float) $cancellation->fresh()->estimated_usage_cost > 0) {
                BillableItem::create([
                    'user_id' => $cancellation->user_id,
                    'customer_service_id' => $cancellation->customer_service_id,
                    'description' => 'Gebruikskosten bij opzegging '.$cancellation->customerService->service->title,
                    'quantity' => 1,
                    'unit_price' => $cancellation->estimated_usage_cost,
                    'total' => $cancellation->estimated_usage_cost,
                    'status' => 'open',
                    'period_start' => $cancellation->requested_at,
                    'period_end' => $cancellation->effective_at,
                ]);
            }
        });
    }

    public function reject(ServiceCancellationRequest $cancellation, ?string $notes): void
    {
        DB::transaction(function () use ($cancellation, $notes) {
            $cancellation->update(['status' => 'rejected', 'admin_notes' => $notes]);
            $cancellation->customerService->update([
                'cancel_at_period_end' => false,
                'cancelled_at' => null,
                'auto_renew' => true,
                'end_date' => $cancellation->original_end_date,
            ]);
        });
    }

    public function processDueApproved(): int
    {
        $processed = 0;
        ServiceCancellationRequest::query()
            ->where('status', 'approved')
            ->whereDate('effective_at', '<=', now())
            ->with('customerService')
            ->chunkById(100, function ($requests) use (&$processed) {
                foreach ($requests as $request) {
                    DB::transaction(function () use ($request) {
                        $request->customerService->update([
                            'status' => 'cancelled',
                            'suspension_reason' => 'cancellation',
                            'end_date' => $request->effective_at,
                            'auto_renew' => false,
                        ]);
                        $request->update(['status' => 'completed']);
                        $this->suspendExternally($request->customerService);
                        $this->createRefundTransaction($request->customerService, $request->effective_at);
                    });
                    $this->notifySuspended($request->customerService->fresh());
                    $processed++;
                }
            });

        return $processed;
    }

    /**
     * Refund policy:
     * - First 7 days after the FIRST payment (renewals do not reset this): 100% back.
     * - Day 8-31 after the first payment: pro-rata refund of the last payment,
     *   based on the unused portion of the current billing period.
     * - After day 31: no automatic refund.
     */
    private function createRefundTransaction(CustomerService $customerService, $effectiveAt = null): void
    {
        $paidQuery = \App\Models\Invoice::where('user_id', $customerService->user_id)
            ->where('status', 'betaald')
            ->where(function ($query) use ($customerService) {
                $query->where('customer_service_id', $customerService->id)
                    ->orWhereHas('lines', fn ($q) => $q->where('customer_service_id', $customerService->id));
            });

        $firstPaid = (clone $paidQuery)->oldest('paid_at')->first();
        $lastPaid = (clone $paidQuery)->latest('paid_at')->first();

        if (! $lastPaid || ! $firstPaid?->paid_at) {
            return;
        }

        $reference = 'refund-'.$lastPaid->invoice_number.'-service-'.$customerService->id;
        if (\App\Models\Transaction::where('reference', $reference)->exists()) {
            return;
        }

        $asOf = ($effectiveAt ?? now())->copy()->startOfDay();
        $daysSinceFirstPayment = $firstPaid->paid_at->copy()->startOfDay()->diffInDays($asOf, false);

        if ($daysSinceFirstPayment > 31) {
            return;
        }

        if ($daysSinceFirstPayment <= 7) {
            $amount = (float) $lastPaid->total;
            $policy = 'volledige terugbetaling binnen 7 dagen';
        } else {
            $periodStart = ($customerService->current_period_start ?? $firstPaid->paid_at)->copy()->startOfDay();
            $periodEnd = ($customerService->current_period_end ?? $this->fallbackPeriodEnd($customerService, $periodStart))->copy()->startOfDay();
            $totalDays = max(1, $periodStart->diffInDays($periodEnd));
            $usedDays = min($totalDays, max(0, $periodStart->diffInDays($asOf, false)));
            $unusedRatio = 1 - ($usedDays / $totalDays);
            $amount = round((float) $lastPaid->total * $unusedRatio, 2);
            $policy = sprintf('pro-rata terugbetaling (%d%% ongebruikt)', (int) round($unusedRatio * 100));
        }

        if ($amount <= 0) {
            return;
        }

        \App\Models\Transaction::create([
            'transaction_number' => \App\Models\Transaction::generateNumber(),
            'user_id' => $customerService->user_id,
            'invoice_id' => $lastPaid->id,
            'amount' => $amount,
            'type' => 'creditering',
            'payment_method' => 'ideal',
            'status' => 'terugbetaald',
            'description' => "Terugbetaling na opzegging {$customerService->service->title} ({$policy}, factuur {$lastPaid->invoice_number})",
            'transaction_date' => now(),
            'reference' => $reference,
        ]);
    }

    private function suspendExternally(CustomerService $customerService): void
    {
        try {
            app(ProvisioningService::class)->suspend($customerService);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    private function notifySuspended(CustomerService $customerService): void
    {
        app(\App\Services\CustomerNotificationService::class)->serviceSuspended(
            $customerService->user,
            $customerService,
            match ($customerService->suspension_reason) {
                'cancellation' => 'uw dienst is opgezegd',
                'payment_overdue' => 'openstaande betaling',
                default => 'uw dienst is tijdelijk opgeschort',
            }
        );
    }

    private function fallbackPeriodEnd(CustomerService $customerService, $periodStart)
    {
        return in_array($customerService->billing_cycle, ['yearly', 'jaarlijks'], true)
            ? $periodStart->copy()->addYear()
            : $periodStart->copy()->addMonthNoOverflow();
    }
}
