<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi PermissionSeeder và RoleSeeder
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            CategoriesSeeder::class,
            ProductSeeder::class,
            CategoryProductSeeder::class

        ]);

        User::factory(50)->create();

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Gán vai trò cho người dùng đã tạo
        $this->call([
            UserRoleSeeder::class,
        ]);
    }
}
