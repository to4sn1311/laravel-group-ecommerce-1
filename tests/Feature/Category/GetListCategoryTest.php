<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetListCategoryTest extends TestCase
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
    private function createCategory(): void
    {
        Category::factory()->create();
    }
    private function getCategoryIndex()
    {
        return $this->get(route('categories.index'));
    }
    /** @test */
    public function authorized_user_can_get_all_category()
    {
        $this->actingAs($this->admin);
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('categories.index');
    }
    /** @test */
    public function unauthorized_user_can_get_all_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);        
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    } 
    /** @test */
    public function unauthenticated_user_can_get_all_category()
    {
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertRedirect('/login');
    }
}
