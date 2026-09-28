<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'role' => 'customer',
            'is_active' => true,
            'phone' => fake()->optional()->phoneNumber(),
            'company' => fake()->optional()->company(),
            'street' => fake()->streetName(),
            'house_number' => (string) fake()->buildingNumber(),
            'postal_code' => fake()->postcode(),
            'city' => fake()->city(),
            'country' => 'NL',
            'kvk_number' => null,
            'vat_number' => null,
        ];
    }
}
