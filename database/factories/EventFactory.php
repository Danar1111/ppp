<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $eventName = fake()->randomElement([
            'Konsolidasi Kader',
            'Baksos Ramadhan',
            'Pelatihan Saksi TPS',
            'Rapat Koordinasi DPC',
            'Sosialisasi Pilkada',
            'Silaturahmi Ulama & Tokoh',
            'Pendidikan Politik Kader',
        ]) . ' ' . fake()->city();

        $start = fake()->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+' . fake()->numberBetween(2, 6) . ' hours');

        return [
            'name' => $eventName,
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'latitude' => fake()->randomFloat(8, -6.9, -6.7),
            'longitude' => fake()->randomFloat(8, 107.8, 108.0),
            'start_datetime' => $start,
            'end_datetime' => $end,
            'status' => fake()->randomElement(['Akan Datang', 'Sedang Berjalan', 'Selesai']),
        ];
    }
}
