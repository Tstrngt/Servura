<?php

namespace Database\Factories;

use App\Models\DomainTld;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainTldFactory extends Factory
{
    protected $model = DomainTld::class;

    public function definition(): array
    {
        return [
            'extension' => '.'.$this->faker->unique()->lexify('???'),
            'is_active' => true,
            'registration_price' => $this->faker->randomFloat(2, 5, 50),
            'renewal_price' => $this->faker->randomFloat(2, 5, 50),
            'transfer_price' => $this->faker->randomFloat(2, 5, 50),
            'cost_price' => null,
            'sort_order' => 0,
        ];
    }
}
