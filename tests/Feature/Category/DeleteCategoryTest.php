<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
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
    public function getDeleteCategoryRoute($id)
    {
        return route('categories.destroy',$id);
    }
    /** @test */
    public function authorized_user_can_delete_category()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $this->assertDatabaseMissing('categories',$category->toArray());
        $response->assertStatus(200);
    }
    /** @test */
    public function unauthorized_user_can_not_delete_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertStatus(403);
    }
    /** @test */
    public function unauthenticated_user_can_not_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_not_delete_category_if_not_found_id()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $category_id = -1;
        $response = $this->delete($this->getDeleteCategoryRoute($category_id));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
