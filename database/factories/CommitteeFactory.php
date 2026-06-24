<?php

namespace Database\Factories;

use App\Models\Committee;
use App\Models\User;
use App\Models\Position;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommitteeFactory extends Factory
{
    protected $model = Committee::class;

    public function definition(): array
    {
        $member = User::inRandomOrder()->first() ?? User::factory();
        $position = Position::inRandomOrder()->first() ?? Position::factory();
        $province = Province::first();
        $regency = Regency::first();
        $district = District::inRandomOrder()->first();
        $village = Village::inRandomOrder()->first();

        return [
            'member_id' => $member instanceof User ? $member->id : $member,
            'position_id' => $position instanceof Position ? $position->id : $position,
            'sk_number' => 'SK/' . fake()->randomElement(['DPC', 'DPW', 'PAC']) . '/' . fake()->year() . '/' . fake()->numerify('###'),
            'start_date' => fake()->date(),
            'end_date' => fake()->optional()->date(),
            'province_id' => $province ? $province->id : null,
            'regency_id' => $regency ? $regency->id : null,
            'district_id' => $district ? $district->id : null,
            'village_id' => $village ? $village->id : null,
        ];
    }
}
