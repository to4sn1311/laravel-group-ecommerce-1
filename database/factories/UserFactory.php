<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

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
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Tạo người dùng với vai trò quản trị viên
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $user->roles()->attach($adminRole->id);
            }
        });
    }

    /**
     * Tạo người dùng với vai trò người dùng thông thường
     */
    public function user(): static
    {
        return $this->afterCreating(function (User $user) {
            $userRole = Role::where('name', 'User')->first();
            if ($userRole) {
                $user->roles()->attach($userRole->id);
            }
        });
    }

    /**
     * Tạo người dùng với vai trò quản lý
     */
    public function manager(): static
    {
        return $this->afterCreating(function (User $user) {
            $managerRole = Role::where('name', 'Manager')->first();
            if ($managerRole) {
                $user->roles()->attach($managerRole->id);
            }
        });
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
}
