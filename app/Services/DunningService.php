<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\Invoice;
use App\Models\InvoiceDunningEvent;
use App\Models\Notification;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Mail;

class DunningService
{
    public function run(): array
    {
        $result = ['overdue' => 0, 'reminders' => 0, 'suspended' => 0, 'failed' => 0];
        $graceDays = BillingSetting::integer('suspension_grace_days', 7);

        Invoice::with(['user', 'customerService.service'])
            ->whereIn('status', ['verzonden', 'openstaand', 'in_behandeling', 'vervallen'])
            ->whereDate('due_date', '<=', today())
            ->orderBy('id')
            ->chunkById(100, function ($invoices) use (&$result, $graceDays) {
                foreach ($invoices as $invoice) {
                    try {
                        $daysOverdue = $invoice->due_date->isBefore(today())
                            ? (int) $invoice->due_date->diffInDays(today())
                            : 0;

                        if ($daysOverdue > 0 && $invoice->status !== 'vervallen') {
                            $invoice->update(['status' => 'vervallen']);
                            $result['overdue']++;
                        }

                        if ($daysOverdue === 0 && $this->recordEvent($invoice, 'due_reminder', ['days_overdue' => 0])) {
                            $this->notify($invoice, 'due_reminder');
                            $result['reminders']++;
                        }

                        if ($daysOverdue >= 3 && $this->recordEvent($invoice, 'overdue_reminder', ['days_overdue' => $daysOverdue])) {
                            $this->notify($invoice, 'overdue_reminder');
                            $result['reminders']++;
                        }

                        if ($daysOverdue >= $graceDays && $invoice->customerService && $this->recordEvent($invoice, 'suspended', ['days_overdue' => $daysOverdue])) {
                            $this->suspend($invoice);
                            $this->notify($invoice, 'suspended');
                            $result['suspended']++;
                        }
                    } catch (\Throwable $exception) {
                        report($exception);
                        $result['failed']++;
                    }
                }
            });

        return $result;
    }

    private function recordEvent(Invoice $invoice, string $type, array $metadata): bool
    {
        $event = InvoiceDunningEvent::firstOrCreate(
            ['invoice_id' => $invoice->id, 'event_type' => $type],
            ['processed_at' => now(), 'metadata' => $metadata]
        );

        return $event->wasRecentlyCreated;
    }

    private function suspend(Invoice $invoice): void
    {
        $customerService = $invoice->customerService;
        if (!$customerService || $customerService->status === 'cancelled') {
            return;
        }

        $customerService->update([
            'status' => 'suspended',
            'suspension_reason' => 'non_payment',
            'suspended_at' => now(),
        ]);

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => get_class($customerService),
            'loggable_id' => $customerService->id,
            'action' => 'geschorst_wanbetaling',
            'description' => "Dienst geschorst wegens onbetaalde factuur {$invoice->invoice_number}",
            'metadata' => ['invoice_id' => $invoice->id],
        ]);
    }

    private function notify(Invoice $invoice, string $type): void
    {
        $content = match ($type) {
            'due_reminder' => [
                'title' => 'Betaaltermijn factuur bereikt',
                'message' => "De betaaltermijn van factuur {$invoice->invoice_number} is vandaag bereikt.",
                'subject' => "Betaalherinnering {$invoice->invoice_number}",
            ],
            'overdue_reminder' => [
                'title' => 'Factuur is vervallen',
                'message' => "Factuur {$invoice->invoice_number} is nog niet betaald. Betaal deze om schorsing te voorkomen.",
                'subject' => "Factuur {$invoice->invoice_number} is vervallen",
            ],
            default => [
                'title' => 'Dienst geschorst wegens wanbetaling',
                'message' => "Uw dienst is geschorst omdat factuur {$invoice->invoice_number} niet is betaald.",
                'subject' => "Dienst geschorst - factuur {$invoice->invoice_number}",
            ],
        };

        Notification::notify(
            $invoice->user,
            'invoice',
            $content['title'],
            $content['message'],
            route('customer.invoices.show', $invoice)
        );

        try {
            Mail::send('dunning-email', ['invoice' => $invoice, 'type' => $type], function ($message) use ($invoice, $content) {
                $message->to($invoice->user->email, $invoice->user->name)->subject($content['subject']);
            });
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
