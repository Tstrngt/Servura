<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerImpersonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_staff_can_impersonate_customer_and_return_to_admin(): void
    {
        $staff = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer', 'is_active' => true]);
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->actingAs($staff)
            ->post(route('admin.customers.impersonate', $customer))
            ->assertRedirect(route('customer.dashboard'))
            ->assertSessionHas('impersonator_id', $staff->id)
            ->assertSessionHas('impersonated_customer_id', $customer->id);

        $this->assertAuthenticatedAs($customer);
        $this->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('Terug naar admin');

        $this->post(route('impersonation.stop'))
            ->assertRedirect(route('admin.customers.show', $customer));

        $this->assertAuthenticatedAs($staff);
        $this->assertFalse(session()->has('impersonator_id'));
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'customer.impersonation.started',
            'object_id' => $customer->id,
            'user_id' => $staff->id,
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'customer.impersonation.stopped',
            'object_id' => $customer->id,
            'user_id' => $staff->id,
        ]);
    }

    public function test_staff_without_permission_cannot_impersonate_customer(): void
    {
        $staff = User::factory()->create(['role' => 'employee', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer', 'is_active' => true]);

        $this->actingAs($staff)
            ->post(route('admin.customers.impersonate', $customer))
            ->assertForbidden();

        $this->assertAuthenticatedAs($staff);
        $this->assertSame(0, AuditLog::count());
    }

    public function test_inactive_customer_cannot_be_impersonated(): void
    {
        $staff = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer', 'is_active' => false]);
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->actingAs($staff)
            ->post(route('admin.customers.impersonate', $customer))
            ->assertNotFound();

        $this->assertAuthenticatedAs($staff);
    }
}
