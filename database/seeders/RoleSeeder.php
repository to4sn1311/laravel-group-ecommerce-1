<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo vai trò Super Admin
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'description' => 'Vua'
        ]);
        // Tạo vai trò Admin
        $adminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Quản trị viên hệ thống với mọi quyền'
        ]);

        // Tạo vai trò Manager
        $managerRole = Role::create([
            'name' => 'Manager',
            'description' => 'Quản lý với các quyền giới hạn'
        ]);

        // Tạo vai trò User
        $userRole = Role::create([
            'name' => 'User',
            'description' => 'Người dùng thông thường'
        ]);

        // Gán tất cả quyền cho Admin
        $permissions = Permission::all();
        $superAdminRole->permissions()->attach($permissions->pluck('id')->toArray());

        // Gán quyền cho Admin (không có quyền xóa và những quyền quản lý cao cấp)
        $adminPermissions = Permission::whereNotIn('name', [
            'user-delete', 'role-delete', 'permission-delete', 
            'role-create', 'role-edit', 'permission-create', 'permission-edit'
        ])->get();
        $adminRole->permissions()->attach($adminPermissions->pluck('id')->toArray());
        
        // Gán quyền cho User (chỉ có quyền xem)
        $userPermissions = Permission::where('name', 'like', '%-list')->get();
        $userRole->permissions()->attach($userPermissions->pluck('id')->toArray());
    }
} 