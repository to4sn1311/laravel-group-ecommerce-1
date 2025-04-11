<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchCategoryTest extends TestCase
{
    protected $admin;
    
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo quyền
        $manageC = Permission::firstOrCreate(['name' => 'category-list']);
        $manageC1 = Permission::firstOrCreate(['name' => 'category-create']);
        $manageC2 = Permission::firstOrCreate(['name' => 'category-edit']);
        $manageC3 = Permission::firstOrCreate(['name' => 'category-delete']);
        // Tạo vai trò
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Gán quyền cho vai trò Admin (chỉ thêm nếu chưa có)
        $adminRole->permissions()->syncWithoutDetaching([$manageC->id, $manageC1->id, $manageC2->id, $manageC3->id]);

        // Tạo tài khoản admin (nếu chưa có)
        $this->admin = User::factory()->create();
        $this->admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
    /** @test */
    public function user_can_search_categories()
    {
        $this->actingAs($this->admin);
        // Tạo danh mục mẫu
        Category::firstOrCreate(['name' => 'Áo Thun1']);
        Category::firstOrCreate(['name' => 'Áo Sơ Mi1']);

        // Gửi request tìm kiếm với từ khóa "Áo"
        $response = $this->getJson(route('categories.search', ['keyword' => 'Áo']));

        $response->assertStatus(200)
        ->assertJsonStructure([
            'categories' => [['id', 'name']],
            'pagination'
        ]);
        // Kiểm tra danh sách categories không rỗng
        $this->assertGreaterThan(0, count($response['categories']));
    }
//
    /** @test */
    public function user_can_search_children_categories()
    {
        $this->actingAs($this->admin);
        // Tạo danh mục cha và danh mục con
        $parent = Category::firstOrCreate(['name' => 'Áo test']);
        Category::firstOrCreate(['name' => 'Áo Vải', 'parent_id' => $parent->id]);
        Category::firstOrCreate(['name' => 'Áo Da', 'parent_id' => $parent->id]);

        // Gửi request tìm kiếm danh mục con của "Thời Trang" với từ khóa "Giày"
        $response = $this->getJson(route('categories.search-children', [
            'parentId' => $parent->id,
            'keyword' => 'Áo'
        ]));

        $response->assertStatus(200)
        ->assertJsonStructure([
            'categories' => [['id', 'name']],
            'pagination'
        ]);
        // Kiểm tra danh sách categories không rỗng
        $this->assertGreaterThan(0, count($response['categories']));         
    }
}
