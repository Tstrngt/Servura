<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use App\Services\PortalTicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TicketFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_ticket_and_staff_receive_database_notification(): void
    {
        $customer = $this->user('customer', 'klant@servura.test');
        $staff = $this->user('admin', 'admin@servura.test');

        $response = $this->actingAs($customer)->post(route('customer.tickets.store'), [
            'title' => 'Homepage aanpassen',
            'description' => 'Ik wil graag de tekst op de homepage laten aanpassen.',
            'priority' => 'medium',
            'category' => 'general',
            'request_type' => 'website_aanpassen',
            'request_details' => ['tekst', 'foto'],
            'page' => 'Homepage',
        ]);

        $ticket = Ticket::firstOrFail();

        $response->assertRedirect(route('customer.tickets.show', $ticket));
        $this->assertSame(['tekst', 'foto'], $ticket->request_details);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $staff->id,
            'type' => 'ticket_created',
        ]);
    }

    public function test_portal_ticket_service_rejects_invalid_priority(): void
    {
        $customer = $this->user('customer', 'klant@servura.test');

        $this->expectException(ValidationException::class);

        app(PortalTicketService::class)->create($customer, [
            'title' => 'Ongeldige aanvraag',
            'description' => 'Deze aanvraag heeft een ongeldige prioriteit.',
            'priority' => 'normal',
        ]);
    }

    public function test_customer_reply_reopens_waiting_ticket_without_sending_mail(): void
    {
        $customer = $this->user('customer', 'klant@servura.test');
        $staff = $this->user('admin', 'admin@servura.test');
        $ticket = Ticket::create([
            'user_id' => $customer->id,
            'title' => 'Vraag',
            'description' => 'Een voldoende lange omschrijving.',
            'status' => 'waiting_for_customer',
            'priority' => 'medium',
            'category' => 'general',
        ]);

        $response = $this->actingAs($customer)->post(route('customer.tickets.reply', $ticket), [
            'message' => 'Hierbij de gevraagde informatie.',
        ]);

        $response->assertRedirect();
        $this->assertSame('open', $ticket->fresh()->status);
        $this->assertDatabaseHas('ticket_replies', [
            'ticket_id' => $ticket->id,
            'user_id' => $customer->id,
            'is_internal' => false,
        ]);
        $this->assertSame(1, Notification::where('user_id', $staff->id)->where('type', 'ticket_reply')->count());
    }

    public function test_admin_can_claim_ticket_atomically_and_resolve_it_with_public_reply(): void
    {
        $customer = $this->user('customer', 'klant@servura.test');
        $admin = $this->user('admin', 'admin@servura.test');
        $ticket = Ticket::create([
            'user_id' => $customer->id,
            'title' => 'Technische vraag',
            'description' => 'Een voldoende lange technische omschrijving.',
            'priority' => 'high',
            'category' => 'technical',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.tickets.claim', $ticket))
            ->assertRedirect();

        $ticket->refresh();
        $this->assertSame($admin->id, $ticket->assigned_to);
        $this->assertSame('in_progress', $ticket->status);

        $this->actingAs($admin)
            ->post(route('admin.tickets.reply', $ticket), [
                'message' => 'Het probleem is opgelost en gecontroleerd.',
                'status_after_reply' => 'resolved',
            ])
            ->assertRedirect(route('admin.tickets.show', $ticket));

        $ticket->refresh();
        $this->assertSame('resolved', $ticket->status);
        $this->assertNotNull($ticket->resolved_at);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'type' => 'ticket_reply',
        ]);
    }

    public function test_customer_cannot_view_another_customers_ticket(): void
    {
        $owner = $this->user('customer', 'eigenaar@servura.test');
        $other = $this->user('customer', 'ander@servura.test');
        $ticket = Ticket::create([
            'user_id' => $owner->id,
            'title' => 'Privé aanvraag',
            'description' => 'Deze aanvraag hoort bij een andere klant.',
            'priority' => 'medium',
            'category' => 'general',
        ]);

        $this->actingAs($other)
            ->get(route('customer.tickets.show', $ticket))
            ->assertForbidden();
    }

    private function user(string $role, string $email): User
    {
        return User::create([
            'name' => ucfirst($role).' gebruiker',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => $role,
            'is_active' => true,
        ]);
    }
}
