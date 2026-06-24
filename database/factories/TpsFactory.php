<?php

namespace Database\Factories;

use App\Models\Tps;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

class TpsFactory extends Factory
{
    protected $model = Tps::class;

    public function definition(): array
    {
        $village = Village::inRandomOrder()->first();

        return [
            'name' => 'TPS ' . fake()->numerify('###') . ' ' . ($village ? $village->name : fake()->streetName()),
            'village_id' => $village ? $village->id : 1,
            'latitude' => fake()->randomFloat(8, -6.9, -6.7),
            'longitude' => fake()->randomFloat(8, 107.8, 108.0),
            'status' => fake()->randomElement(['Pending', 'Selesai']),
        ];
    }
}
