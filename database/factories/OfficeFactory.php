<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    protected $model = Office::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Kantor DPC PPP', 'Posko PPP', 'Sekretariat PAC PPP']) . ' ' . fake()->streetName(),
            'type' => fake()->randomElement(['Utama', 'Cabang', 'Posko']),
            'address' => fake()->address(),
            'latitude' => fake()->randomFloat(8, -6.9, -6.7),
            'longitude' => fake()->randomFloat(8, 107.8, 108.0),
            'radius_meters' => fake()->randomElement([30, 50, 75, 100]),
        ];
    }
}
