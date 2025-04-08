<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 1 admin
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        // Tạo 2 quản lý
        User::factory()->manager()->count(2)->create();

        // Tạo 10 người dùng thông thường
        User::factory()->user()->count(10)->create();
    }
} 