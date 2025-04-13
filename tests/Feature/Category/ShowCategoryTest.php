<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;
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
    public function showCategoryViewRoute($id){
        return route('categories.show',$id);
    }
    protected function createCategory()
    {
        return Category::factory()->create([
            'name' => 'Áo abcdefch',
            'parent_id'=>null
        ]);
    }
    /** @test */
    public function unauthenticated_user_can_not_show_category()
    {
        $category = $this->createCategory();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertRedirect('/login');
    }
    /** @test */
    public function authorized_user_can_show_category()
    {
        $this->actingAs($this->admin);
        $category = $this->createCategory();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertViewIs('categories.show');
    }
    /** @test */
    public function unthorized_user_can_not_show_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = $this->createCategory();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
