<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $levels = ['DPP', 'DPW', 'DPC', 'PAC', 'Ranting'];
        $level = fake()->randomElement($levels);

        return [
            'name' => fake()->jobTitle() . ' ' . $level,
            'level' => $level,
            'description' => fake()->sentence(),
        ];
    }
}
