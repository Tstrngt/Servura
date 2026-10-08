<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCustomerCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_an_active_customer_from_admin_form(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($owner)->post(route('admin.customers.store'), [
            'name' => 'STC De Rijnstreek',
            'email' => 'administratie@stc-de-rijnstreek.test',
            'company' => 'STC De Rijnstreek',
            'country' => 'Nederland',
            'password' => 'VeiligWachtwoord123!',
            'password_confirmation' => 'VeiligWachtwoord123!',
            'is_active' => 'on',
        ]);

        $customer = User::where('email', 'administratie@stc-de-rijnstreek.test')->firstOrFail();

        $response->assertRedirect(route('admin.customers.show', $customer));
        $response->assertSessionHasNoErrors();
        $this->assertTrue($customer->isCustomer());
        $this->assertTrue($customer->is_active);
    }

    public function test_owner_can_create_an_inactive_customer_from_admin_form(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);

        $this->actingAs($owner)->post(route('admin.customers.store'), [
            'name' => 'Inactieve klant',
            'email' => 'inactief@example.test',
            'password' => 'VeiligWachtwoord123!',
            'password_confirmation' => 'VeiligWachtwoord123!',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'inactief@example.test',
            'role' => 'customer',
            'is_active' => false,
        ]);
    }
}
