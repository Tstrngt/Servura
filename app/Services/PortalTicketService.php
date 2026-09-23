<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PortalTicketService
{
    private const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    private const CATEGORIES = ['technical', 'billing', 'general', 'feature_request', 'bug_report'];

    public function create(User $customer, array $attributes): Ticket
    {
        if (! $customer->isCustomer()) {
            throw ValidationException::withMessages(['customer' => 'Alleen klanten kunnen een aanvraag indienen.']);
        }

        $priority = Arr::get($attributes, 'priority', 'medium');
        $category = Arr::get($attributes, 'category', 'general');

        if (! in_array($priority, self::PRIORITIES, true)) {
            throw ValidationException::withMessages(['priority' => 'Ongeldige prioriteit.']);
        }

        if (! in_array($category, self::CATEGORIES, true)) {
            throw ValidationException::withMessages(['category' => 'Ongeldige categorie.']);
        }

        return DB::transaction(function () use ($customer, $attributes, $priority, $category) {
            $ticket = Ticket::create([
                'user_id' => $customer->id,
                'customer_service_id' => Arr::get($attributes, 'customer_service_id'),
                'title' => Arr::get($attributes, 'title'),
                'description' => Arr::get($attributes, 'description'),
                'priority' => $priority,
                'category' => $category,
                'request_type' => Arr::get($attributes, 'request_type'),
                'request_details' => array_values(array_filter(Arr::get($attributes, 'request_details', []))),
                'page' => Arr::get($attributes, 'page'),
                'customer_notes' => Arr::get($attributes, 'customer_notes'),
            ]);

            User::staff()->each(function (User $staff) use ($customer, $ticket) {
                Notification::notify(
                    $staff,
                    'ticket_created',
                    'Nieuwe aanvraag',
                    $customer->name.' heeft aanvraag '.$ticket->ticket_number.' ingediend.',
                    route('admin.tickets.show', $ticket)
                );
            });

            return $ticket;
        });
    }
}
