<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    protected $admin;
    const INVALID_ID=-1;
    const ADMIN_PERMISSIONS = ['category-list', 'category-create', 'category-edit', 'category-delete'];
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAdminWithPermissions();
    }
    protected function setUpAdminWithPermissions()
    {
        foreach (self::ADMIN_PERMISSIONS as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->permissions()->syncWithoutDetaching(Permission::whereIn('name', self::ADMIN_PERMISSIONS)->pluck('id'));
    
        $this->admin = User::factory()->create();
        $this->admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
    /** @test */
    public function authorized_user_can_delete_category()
    {
        $this->actingAs($this->admin);
        $category = $this->createCategory();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('categories',['id' => $category->id]);
    }
    /** @test */
    public function unauthorized_user_can_not_delete_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = $this->createCategory();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
    /** @test */
    public function unauthenticated_user_can_not_delete_category()
    {
        $category = $this->createCategory();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertRedirect('/login');
    }
    /** @test */
    public function authenticated_user_can_not_delete_category_if_not_found_id()
    {
        $this->actingAs($this->admin);
        $category_id = self::INVALID_ID;
        $response = $this->delete($this->getDeleteCategoryRoute($category_id));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
    protected function createCategory()
    {
        return Category::factory()->create();
    }
    public function getDeleteCategoryRoute($id)
    {
        return route('categories.destroy',$id);
    }
}
