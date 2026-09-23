<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TicketStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_transitions_keep_timestamps_consistent(): void
    {
        $customer = User::create([
            'name' => 'Testklant',
            'email' => 'status@servura.test',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);
        $ticket = Ticket::create([
            'user_id' => $customer->id,
            'title' => 'Statuscontrole',
            'description' => 'Controle van alle statussen en tijdstempels.',
            'priority' => 'medium',
            'category' => 'general',
        ]);

        $ticket->markAsResolved('Afgerond');
        $this->assertSame('resolved', $ticket->fresh()->status);
        $this->assertNotNull($ticket->fresh()->resolved_at);

        $ticket->reopen();
        $this->assertSame('open', $ticket->fresh()->status);
        $this->assertNull($ticket->fresh()->resolved_at);
        $this->assertNull($ticket->fresh()->closed_at);

        $ticket->markAsClosed('Gesloten');
        $this->assertSame('closed', $ticket->fresh()->status);
        $this->assertNotNull($ticket->fresh()->closed_at);
    }
}
