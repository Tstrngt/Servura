<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

/**
 * Customer-facing transactional e-mails.
 * Always catches mailer exceptions so a failing e-mail never breaks a workflow.
 */
class CustomerNotificationService
{
    public function accountCreated(User $user, string $plainPassword): void
    {
        $this->send($user, 'account-created', [
            'user' => $user,
            'plainPassword' => $plainPassword,
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

        if (! $user->email_verification_token) {
            $user->update(['email_verification_token' => \Illuminate\Support\Str::random(48)]);
        }

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

    public function ticketReplied(User $user, Ticket $ticket, bool $byCustomer = false): void
    {
        if ($byCustomer) {
            return;
        }

        $this->send($user, 'ticket-replied', [
            'user' => $user,
            'ticket' => $ticket,
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
        $this->send($user, 'invoice-ready', [
            'user' => $user,
            'invoice' => $invoice,
            'invoiceUrl' => route('customer.invoices.show', $invoice),
        ]);
    }

    public function quoteReady(User $user, Quote $quote): void
    {
        $this->send($user, 'quote-ready', [
            'user' => $user,
            'quote' => $quote,
            'quoteUrl' => route('customer.quotes.show', $quote),
        ]);
    }

    private function send(User $user, string $view, array $data): void
    {
        try {
            Mail::send("emails.{$view}", $data, function ($message) use ($user, $view) {
                $message->to($user->email, $user->name)
                    ->subject($this->subjectFor($view));
            });
        } catch (\Throwable $e) {
            report($e);
        }
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
            'invoice-ready' => 'Er staat een factuur voor u klaar',
            'quote-ready' => 'Er staat een offerte voor u klaar',
            default => 'Bericht van '.config('site.name', config('app.name')),
        };
    }
}
