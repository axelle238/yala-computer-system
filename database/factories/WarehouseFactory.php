<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Gudang '.fake()->city(),
            'location' => fake()->address(),
            'is_active' => true,
        ];
    }
}
