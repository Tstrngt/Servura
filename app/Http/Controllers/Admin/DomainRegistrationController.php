<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerService;
use App\Models\DomainRegistration;
use App\Models\AuditLog;
use App\Models\Invoice;
use App\Services\DomainRegistrationService;
use Illuminate\Support\Facades\DB;

class DomainRegistrationController extends Controller
{
    private function authorizeOwner(): void
    {
        abort_unless(auth()->user()?->isOwner(), 403, 'Alleen eigenaars hebben toegang.');
    }

    public function index()
    {
        $this->authorizeOwner();

        $domains = DomainRegistration::with(['user', 'order'])
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('admin.domains.index', compact('domains'));
    }

    public function show(DomainRegistration $domainRegistration)
    {
        $this->authorizeOwner();

        $domainRegistration->load(['user', 'order', 'customerService']);

        return view('admin.domains.show', compact('domainRegistration'));
    }

    public function retry(DomainRegistration $domainRegistration, DomainRegistrationService $service)
    {
        $this->authorizeOwner();
        abort_unless(in_array($domainRegistration->status, [
            DomainRegistration::STATUS_REGISTRATION_FAILED,
            DomainRegistration::STATUS_TRANSFER_FAILED,
        ], true), 422);

        $domainRegistration->loadMissing(['customerService', 'order.invoice']);
        $invoice = $domainRegistration->order?->invoice;
        abort_unless($invoice && ($invoice->status === 'betaald' || (float) $invoice->total <= 0), 422, 'Alleen een betaalde registratie kan opnieuw worden geprobeerd.');
        abort_unless($domainRegistration->customerService, 422, 'Gekoppelde dienst ontbreekt.');

        $result = $domainRegistration->type === DomainRegistration::TYPE_TRANSFER
            ? $service->transfer($domainRegistration->customerService)
            : $service->register($domainRegistration->customerService);

        AuditLog::record('domain.registration.retried', $domainRegistration, null, [
            'status' => $result->status,
            'provider' => $result->provider,
        ]);

        return back()->with(
            $result->status === DomainRegistration::STATUS_ACTIVE ? 'success' : 'error',
            $result->status === DomainRegistration::STATUS_ACTIVE
                ? 'Domeinregistratie is alsnog succesvol uitgevoerd.'
                : 'De provider heeft de registratie opnieuw geweigerd: '.$result->error_message
        );
    }

    public function destroy(DomainRegistration $domainRegistration)
    {
        $this->authorizeOwner();

        if (! $this->canDelete($domainRegistration)) {
            return redirect()->route('admin.domains.index')
                ->with('error', 'Dit domein kan niet worden verwijderd omdat het al betaald, actief of gekoppeld is aan een actieve hostingdienst.');
        }

        DB::transaction(function () use ($domainRegistration) {
            $customerService = $domainRegistration->customerService;

            if ($customerService) {
                $this->removeUnpaidInvoiceLines($customerService);
                $this->removeOrderLines($customerService);
                $customerService->delete();
            }

            $domainRegistration->delete();
        });

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domeinregistratie is verwijderd.');
    }

    private function canDelete(DomainRegistration $domainRegistration): bool
    {
        $deletableStatuses = [
            DomainRegistration::STATUS_PENDING,
            DomainRegistration::STATUS_AWAITING_PAYMENT,
            DomainRegistration::STATUS_REGISTRATION_FAILED,
            DomainRegistration::STATUS_TRANSFER_PENDING,
            DomainRegistration::STATUS_TRANSFER_FAILED,
            DomainRegistration::STATUS_CANCELLED,
        ];

        if (! in_array($domainRegistration->status, $deletableStatuses, true)) {
            return false;
        }

        $customerService = $domainRegistration->customerService;
        if ($customerService && $customerService->status !== 'suspended') {
            return false;
        }

        if ($customerService && $customerService->provisioning_status !== 'pending_payment') {
            return false;
        }

        $hostedService = $domainRegistration->hostedCustomerService;
        if ($hostedService && $hostedService->status === 'active') {
            return false;
        }

        return true;
    }

    private function removeUnpaidInvoiceLines(CustomerService $customerService): void
    {
        $invoices = $customerService->invoices()->where('status', '!=', 'betaald')->get();

        foreach ($invoices as $invoice) {
            $invoice->lines()->where('customer_service_id', $customerService->id)->delete();

            if ($invoice->lines()->count() === 0) {
                $invoice->delete();
            } else {
                $this->recalculateInvoiceTotals($invoice);
            }
        }
    }

    private function removeOrderLines(CustomerService $customerService): void
    {
        $orders = $customerService->orders()->get();

        foreach ($orders as $order) {
            $order->lines()->where('customer_service_id', $customerService->id)->delete();

            if ($order->lines()->count() === 0) {
                $order->delete();
            }
        }
    }

    private function recalculateInvoiceTotals(Invoice $invoice): void
    {
        $subtotal = (float) $invoice->lines()->sum('total');
        $vat = round($subtotal * ($invoice->vat_percentage / 100), 2);
        $total = $subtotal + $vat;

        $invoice->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vat,
            'total' => $total,
        ]);
    }
}
