<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gunawan', 'Hani', 'Indra', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nina', 'Putri', 'Rudi', 'Sari', 'Tono', 'Umi', 'Vina', 'Wati', 'Yuni', 'Zainal'];
        $lastNames = ['Saputra', 'Wijaya', 'Susanto', 'Hidayat', 'Kusuma', 'Nugraha', 'Pratama', 'Ramadhan', 'Siregar', 'Tanuwijaya', 'Utomo', 'Wibowo', 'Yuliana', 'Zulkarnain'];

        return [
            'name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the model factory to create admin user.
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('Admin');
        });
    }

    /**
     * Configure the model factory to create owner user.
     */
    public function owner(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('Owner');
        });
    }

    /**
     * Configure the model factory to create pelanggan user with pelanggan data.
     */
    public function pelanggan(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('Pelanggan');

            // Create pelanggan data for this user
            Pelanggan::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }

    /**
     * Configure the model factory to create user with specific role.
     */
    public function withRole(string $role): static
    {
        return $this->afterCreating(function (User $user) use ($role) {
            $user->assignRole($role);

            // If role is pelanggan, create pelanggan data
            if ($role === 'Pelanggan') {
                Pelanggan::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
