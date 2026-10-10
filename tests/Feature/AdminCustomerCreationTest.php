<?php

namespace Tests\Feature;

use App\Models\CustomerEmailLog;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminCustomerCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

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
        $this->assertNotNull($customer->email_verification_token);
        $this->assertDatabaseHas('customer_email_logs', [
            'user_id' => $customer->id,
            'template' => 'account-created',
            'status' => 'sent',
        ]);
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

    public function test_authorized_admin_can_send_and_review_customer_email(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $this->seed(RolesAndPermissionsSeeder::class);

        $response = $this->actingAs($owner)->post(route('admin.customers.emails.send', $customer), [
            'subject' => 'Uw Servura-account',
            'message' => "Hallo,\nDit is een testbericht.",
        ]);

        $response->assertRedirect(route('admin.customers.show', [$customer, 'tab' => 'emails']));
        $response->assertSessionHas('success');
        $log = CustomerEmailLog::where('user_id', $customer->id)->firstOrFail();
        $this->assertSame('sent', $log->status);
        $this->assertSame($owner->id, $log->sent_by);
        $this->assertStringContainsString('Dit is een testbericht.', $log->body_html);

        $this->actingAs($owner)
            ->get(route('admin.customers.show', [$customer, 'tab' => 'emails']))
            ->assertOk()
            ->assertSee('Uw Servura-account');
    }

    public function test_authorized_admin_can_send_standard_password_reset_and_verification_emails(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer', 'email_verified_at' => null]);
        $this->seed(RolesAndPermissionsSeeder::class);

        foreach (['password-reset', 'verify-email'] as $template) {
            $this->actingAs($owner)
                ->post(route('admin.customers.emails.standard', $customer), ['template' => $template])
                ->assertRedirect(route('admin.customers.show', [$customer, 'tab' => 'emails']))
                ->assertSessionHas('success');
        }

        $this->assertDatabaseHas('password_reset_tokens', ['email' => $customer->email]);
        $this->assertDatabaseHas('customer_email_logs', [
            'user_id' => $customer->id,
            'template' => 'password-reset',
            'status' => 'sent',
        ]);
        $this->assertDatabaseHas('customer_email_logs', [
            'user_id' => $customer->id,
            'template' => 'verify-email',
            'status' => 'sent',
        ]);
        $this->assertNotNull($customer->fresh()->email_verification_token);
    }
}
