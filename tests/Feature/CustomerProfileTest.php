<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_update_personal_and_business_details(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer)->put(route('customer.profile.update'), [
            'name' => 'Nieuwe Naam',
            'email' => $customer->email,
            'phone' => '0612345678',
            'company' => 'Servura Klant BV',
            'street' => 'Teststraat',
            'house_number' => '12A',
            'postal_code' => '1234 AB',
            'city' => 'Utrecht',
            'country' => 'Nederland',
            'kvk_number' => '12345678',
            'vat_number' => 'NL123456789B01',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'name' => 'Nieuwe Naam',
            'company' => 'Servura Klant BV',
            'city' => 'Utrecht',
        ]);
    }

    public function test_email_change_requires_current_password(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer)->put(route('customer.profile.update'), [
            'name' => $customer->name,
            'email' => 'nieuw@servura.test',
        ])->assertSessionHasErrors('current_password');

        $this->assertSame($customer->email, $customer->fresh()->email);
    }

    public function test_customer_can_upload_and_remove_logo(): void
    {
        Storage::fake('public');
        $customer = $this->customer();

        $this->actingAs($customer)->put(route('customer.profile.logo'), [
            'profile_logo' => UploadedFile::fake()->image('logo.png', 300, 300),
        ])->assertRedirect();

        $path = $customer->fresh()->profile_logo_path;
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);

        $this->actingAs($customer)->put(route('customer.profile.logo'), [
            'remove_profile_logo' => true,
        ])->assertRedirect();

        $this->assertNull($customer->fresh()->profile_logo_path);
        Storage::disk('public')->assertMissing($path);
    }

    private function customer(): User
    {
        return User::create([
            'name' => 'Testklant',
            'email' => 'profiel@servura.test',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }
}
