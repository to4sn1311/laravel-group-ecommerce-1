<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách các quyền cơ bản
        $permissions = [
            // Quản lý người dùng
            ['name' => 'user-list', 'description' => 'Xem danh sách người dùng'],
            ['name' => 'user-create', 'description' => 'Tạo người dùng mới'],
            ['name' => 'user-edit', 'description' => 'Chỉnh sửa thông tin người dùng'],
            ['name' => 'user-delete', 'description' => 'Xóa người dùng'],
            
            // Quản lý vai trò
            ['name' => 'role-list', 'description' => 'Xem danh sách vai trò'],
            ['name' => 'role-create', 'description' => 'Tạo vai trò mới'],
            ['name' => 'role-edit', 'description' => 'Chỉnh sửa vai trò'],
            ['name' => 'role-delete', 'description' => 'Xóa vai trò'],
            
            // Quản lý quyền
            ['name' => 'permission-list', 'description' => 'Xem danh sách quyền'],
            ['name' => 'permission-create', 'description' => 'Tạo quyền mới'],
            ['name' => 'permission-edit', 'description' => 'Chỉnh sửa quyền'],
            ['name' => 'permission-delete', 'description' => 'Xóa quyền'],
            
            // Quản lý danh mục
            ['name' => 'category-list', 'description' => 'Xem danh sách danh mục'],
            ['name' => 'category-create', 'description' => 'Tạo danh mục mới'],
            ['name' => 'category-edit', 'description' => 'Chỉnh sửa danh mục'],
            ['name' => 'category-delete', 'description' => 'Xóa danh mục'],
            
            // Quản lý sản phẩm
            ['name' => 'product-list', 'description' => 'Xem danh sách sản phẩm'],
            ['name' => 'product-create', 'description' => 'Tạo sản phẩm mới'],
            ['name' => 'product-edit', 'description' => 'Chỉnh sửa sản phẩm'],
            ['name' => 'product-delete', 'description' => 'Xóa sản phẩm'],
        ];

        // Tạo các quyền
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 