<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
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
    public function showCategoryViewRoute($id){
        return route('categories.show',$id);
    }
    /** @test */
    public function unauthenticated_user_can_not_show_category()
    {
        $category = Category::factory()->create();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertRedirect('/login');
    }
    /** @test */
    public function authorized_user_can_show_category()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertViewIs('categories.show');
    }
    /** @test */
    public function unthorized_user_can_not_show_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertStatus(403);
    }
}
