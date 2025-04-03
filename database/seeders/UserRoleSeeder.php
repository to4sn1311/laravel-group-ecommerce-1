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
        // Lấy vai trò Super Admin
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        // $adminRole = Role::where('name', 'Admin')->first();
        
        // Gán vai trò Admin cho người dùng test
        $user = User::where('email', 'admin@gmail.com')->first();
        if ($user && $superAdminRole) {
            $user->roles()->attach($superAdminRole->id);
        }
    }
} 