<?php

namespace Tests\Feature;

use App\Models\CustomerService;
use App\Models\ServerConnection;
use App\Models\Service;
use App\Models\User;
use App\Services\DirectAdminClient;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class ExistingDirectAdminServiceImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_link_existing_account_without_provisioning_invoice_or_email(): void
    {
        Mail::fake();
        [$admin, $customer, $service] = $this->setupRecords();
        $client = Mockery::mock(DirectAdminClient::class);
        $client->shouldReceive('using')->once()->withArgs(fn ($connection) => $connection->is($service->serverConnection))->andReturnSelf();
        $client->shouldReceive('getUserConfig')->once()->with('stcrijn')->andReturn([
            'package' => 'Custom_STC_De_Rijnstreek',
            'domain' => 'stc-de-rijnstreek.nl',
        ]);
        $client->shouldReceive('createUser')->never();
        $this->app->instance(DirectAdminClient::class, $client);

        $response = $this->actingAs($admin)->post(route('admin.customers.services.import-directadmin', $customer), $this->payload($service));

        $response->assertRedirect(route('admin.customers.show', [$customer, 'tab' => 'services']));
        $response->assertSessionHas('success');
        $linked = CustomerService::firstOrFail();
        $this->assertSame('active', $linked->status);
        $this->assertSame('active', $linked->provisioning_status);
        $this->assertSame('stcrijn', $linked->external_username);
        $this->assertNull($linked->external_password);
        $this->assertDatabaseCount('invoices', 0);
        $this->assertDatabaseHas('audit_logs', ['action' => 'existing_directadmin_service_imported']);
        Mail::assertNothingSent();
    }

    public function test_import_is_rejected_when_directadmin_package_does_not_match(): void
    {
        [$admin, $customer, $service] = $this->setupRecords();
        $client = Mockery::mock(DirectAdminClient::class);
        $client->shouldReceive('using')->andReturnSelf();
        $client->shouldReceive('getUserConfig')->andReturn(['package' => 'Ander_Pakket', 'domain' => 'stc-de-rijnstreek.nl']);
        $this->app->instance(DirectAdminClient::class, $client);

        $this->actingAs($admin)->post(route('admin.customers.services.import-directadmin', $customer), $this->payload($service))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('customer_services', 0);
        $this->assertDatabaseCount('invoices', 0);
    }

    public function test_user_config_is_requested_with_get_query_parameter(): void
    {
        [, , $service] = $this->setupRecords();
        Http::fake([
            'https://server.example.test:2222/CMD_API_SHOW_USER_CONFIG*' => Http::response([
                'package' => 'Custom_STC_De_Rijnstreek', 'domain' => 'stc-de-rijnstreek.nl',
            ]),
        ]);

        $config = app(DirectAdminClient::class)->using($service->serverConnection)->getUserConfig('stcrijn');

        $this->assertSame('Custom_STC_De_Rijnstreek', $config['package']);
        Http::assertSent(fn ($request) => $request->method() === 'GET'
            && str_starts_with($request->url(), 'https://server.example.test:2222/CMD_API_SHOW_USER_CONFIG?')
            && $request['user'] === 'stcrijn' && $request['json'] === 'yes');
    }

    private function setupRecords(): array
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $this->seed(RolesAndPermissionsSeeder::class);
        $connection = ServerConnection::create([
            'name' => 'Server 1', 'provider' => 'directadmin', 'url' => 'https://server.example.test:2222',
            'username' => 'reseller', 'password' => 'secret', 'shared_ip' => '192.0.2.10',
            'verify_ssl' => true, 'timeout' => 20, 'is_active' => true,
        ]);
        $service = Service::create([
            'title' => 'Maatwerk hosting - STC De Rijnstreek', 'slug' => 'maatwerk-stc-de-rijnstreek',
            'service_type' => 'hosting', 'fulfillment_type' => 'directadmin', 'server_connection_id' => $connection->id,
            'provider_package' => 'Custom_STC_De_Rijnstreek', 'short_description' => 'Maatwerk hosting',
            'description' => 'Bestaand pakket', 'price' => 39.95, 'price_type' => 'yearly', 'is_active' => true,
        ]);

        return [$admin, $customer, $service];
    }

    private function payload(Service $service): array
    {
        return [
            'service_id' => $service->id, 'external_username' => 'stcrijn', 'domain' => 'stc-de-rijnstreek.nl',
            'price' => 39.95, 'billing_cycle' => 'yearly', 'start_date' => '2025-01-01',
            'current_period_start' => '2026-01-01', 'current_period_end' => '2026-12-31',
            'next_invoice_date' => '2026-12-17', 'payment_method' => 'payment_link', 'auto_renew' => '1',
        ];
    }
}
