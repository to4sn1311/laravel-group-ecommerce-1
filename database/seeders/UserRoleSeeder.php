<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy vai trò Admin
        $adminRole = Role::where('name', 'Admin')->first();
        
        // Gán vai trò Admin cho người dùng test
        $user = User::where('email', 'test@example.com')->first();
        if ($user && $adminRole) {
            $user->roles()->attach($adminRole->id);
        }
    }
} 