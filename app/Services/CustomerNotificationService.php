<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

/**
 * Customer-facing transactional e-mails.
 * Always catches mailer exceptions so a failing e-mail never breaks a workflow.
 */
class CustomerNotificationService
{
    public function accountCreated(User $user): void
    {
        $this->send($user, 'account-created', [
            'user' => $user,
            'verificationUrl' => route('verification.verify', ['token' => $user->email_verification_token]),
        ]);
    }

    public function orderPlaced(User $user, mixed $order): void
    {
        $this->send($user, 'order-placed', [
            'user' => $user,
            'order' => $order,
            'orderUrl' => route('customer.invoices.show', $order->invoice),
        ]);
    }

    public function serviceActivated(User $user, CustomerService $customerService): void
    {
        if ($customerService->service->fulfillment_type === 'directadmin') {
            $this->send($user, 'hosting-activated', [
                'user' => $user,
                'service' => $customerService,
                'loginUrl' => route('customer.services.directadmin-login', $customerService),
            ]);
        }
    }

    public function serviceSuspended(User $user, CustomerService $customerService, string $reason): void
    {
        $this->send($user, 'service-suspended', [
            'user' => $user,
            'service' => $customerService,
            'reason' => $reason,
            'dashboardUrl' => route('customer.dashboard'),
        ]);
    }

    public function sendEmailVerification(User $user): bool
    {
        if ($user->email_verified_at) {
            return false;
        }

        $user->update(['email_verification_token' => \Illuminate\Support\Str::random(48)]);

        $this->send($user, 'verify-email', [
            'user' => $user,
            'verificationUrl' => route('verification.verify', ['token' => $user->email_verification_token]),
        ]);

        return true;
    }

    public function ticketCreated(User $user, Ticket $ticket): void
    {
        $this->send($user, 'ticket-created', [
            'user' => $user,
            'ticket' => $ticket,
            'ticketUrl' => route('customer.tickets.show', $ticket),
        ]);
    }

    public function ticketReplied(User $user, Ticket $ticket, TicketReply $reply, bool $byCustomer = false): void
    {
        if ($byCustomer) {
            return;
        }

        $this->send($user, 'ticket-replied', [
            'user' => $user,
            'ticket' => $ticket,
            'reply' => $reply,
            'ticketUrl' => route('customer.tickets.show', $ticket),
        ]);
    }

    public function ticketClosed(User $user, Ticket $ticket): void
    {
        $this->send($user, 'ticket-closed', [
            'user' => $user,
            'ticket' => $ticket,
            'ticketUrl' => route('customer.tickets.show', $ticket),
        ]);
    }

    public function invoiceReady(User $user, Invoice $invoice): void
    {
        $invoice->loadMissing(['lines', 'user']);
        $this->send($user, 'invoice-ready', [
            'user' => $user,
            'invoice' => $invoice,
            'invoiceUrl' => route('customer.invoices.show', $invoice),
        ], [[
            'data' => Pdf::loadView('pdf.invoice', compact('invoice'))->setPaper('a4')->output(),
            'name' => $invoice->invoice_number.'.pdf',
        ]]);
    }

    public function quoteReady(User $user, Quote $quote): void
    {
        $quote->loadMissing(['lines', 'user']);
        $this->send($user, 'quote-ready', [
            'user' => $user,
            'quote' => $quote,
            'quoteUrl' => route('customer.quotes.show', $quote),
        ], [[
            'data' => Pdf::loadView('pdf.quote', compact('quote'))->setPaper('a4')->output(),
            'name' => $quote->quote_number.'.pdf',
        ]]);
    }

    public function paymentConfirmed(User $user, Invoice $invoice): void
    {
        $invoice->loadMissing(['lines', 'user']);
        $this->send($user, 'payment-confirmed', [
            'user' => $user,
            'invoice' => $invoice,
            'invoiceUrl' => route('customer.invoices.show', $invoice),
        ], [[
            'data' => Pdf::loadView('pdf.invoice', compact('invoice'))->setPaper('a4')->output(),
            'name' => $invoice->invoice_number.'.pdf',
        ]]);
    }

    private function send(User $user, string $view, array $data, array $attachments = []): void
    {
        try {
            $subject = BillingSetting::valueFor("email_template_{$view}_subject", '') ?: $this->subjectFor($view);
            $customHtml = BillingSetting::valueFor("email_template_{$view}_html", '');
            $variables = $this->variables($user, $data);
            $subject = strtr($subject, $variables);
            $callback = function ($message) use ($user, $subject, $attachments) {
                $message->to($user->email, $user->name)->subject($subject);
                foreach ($attachments as $attachment) {
                    $message->attachData($attachment['data'], $attachment['name'], ['mime' => 'application/pdf']);
                }
            };

            if ($customHtml !== '') {
                Mail::html(strtr($customHtml, $variables), $callback);
            } else {
                Mail::send("emails.{$view}", $data, $callback);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function variables(User $user, array $data): array
    {
        $ticket = $data['ticket'] ?? null;
        $invoice = $data['invoice'] ?? ($data['order']->invoice ?? null);
        $quote = $data['quote'] ?? null;
        $service = $data['service'] ?? null;
        $url = $data['verificationUrl'] ?? $data['orderUrl'] ?? $data['loginUrl'] ?? $data['dashboardUrl'] ?? $data['ticketUrl'] ?? $data['invoiceUrl'] ?? $data['quoteUrl'] ?? '';

        return [
            '{{klant_naam}}' => e($user->name), '{{klant_email}}' => e($user->email),
            '{{ticket_nummer}}' => e($ticket?->ticket_number ?? ''), '{{ticket_titel}}' => e($ticket?->title ?? ''),
            '{{reactie}}' => nl2br(e($data['reply']->message ?? '')), '{{factuur_nummer}}' => e($invoice?->invoice_number ?? ''),
            '{{offerte_nummer}}' => e($quote?->quote_number ?? ''), '{{bedrag}}' => number_format((float) ($invoice?->total ?? $quote?->total ?? 0), 2, ',', '.'),
            '{{dienst_naam}}' => e($service?->service?->title ?? ''), '{{domein}}' => e($service?->domain ?? ''),
            '{{gebruikersnaam}}' => e($service?->external_username ?? ''), '{{wachtwoord}}' => e($service?->external_password ?? ''),
            '{{reden}}' => e($data['reason'] ?? ''), '{{actie_url}}' => e($url), '{{site_naam}}' => e(config('site.name', config('app.name'))),
        ];
    }

    private function subjectFor(string $view): string
    {
        return match ($view) {
            'account-created' => 'Welkom bij '.config('site.name', config('app.name')).' — bevestig uw e-mailadres',
            'verify-email' => 'Bevestig uw e-mailadres',
            'order-placed' => 'Bedankt voor uw bestelling',
            'hosting-activated' => 'Uw hostingaccount is actief',
            'service-suspended' => 'Uw dienst is tijdelijk opgeschort',
            'ticket-created' => 'We hebben uw aanvraag ontvangen',
            'ticket-replied' => 'Er is gereageerd op uw aanvraag',
            'ticket-closed' => 'Uw aanvraag is gesloten',
            'invoice-ready' => 'Uw factuur staat klaar',
            'quote-ready' => 'Uw persoonlijke offerte staat klaar',
            'payment-confirmed' => 'Betaling ontvangen — bedankt',
            default => 'Bericht van '.config('site.name', config('app.name')),
        };
    }
}
