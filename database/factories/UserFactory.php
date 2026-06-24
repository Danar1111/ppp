<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $status = fake()->randomElement(['Aktif', 'Aktif', 'Aktif', 'Pending', 'Nonaktif']);
        $village = Village::inRandomOrder()->first();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'nik' => fake()->unique()->numerify('3211############'),
            'phone' => fake()->numerify('08##########'),
            'address' => fake()->address(),
            'village_id' => $village ? $village->id : null,
            'photo' => null,
            'status' => $status,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
