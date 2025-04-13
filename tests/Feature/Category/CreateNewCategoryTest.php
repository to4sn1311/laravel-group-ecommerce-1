<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateNewCategoryTest extends TestCase
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
    public function authorized_user_can_new_category()
    {
        $this->actingAs($this->admin);
        $category=$this->makeCategory();
        $response = $this->post($this->getCreateCategoryRoute(),$category);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('categories',$category);
    }
    /** @test */
    public function unauthorized_user_cannot_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category=$this->makeCategory();
        $response = $this->post($this->getCreateCategoryRoute(),$category);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
    /** @test */
    public function unauthenticated_user_can_not_create_category()
    {
        $category=$this->makeCategory();
        $response = $this->post($this->getCreateCategoryRoute(),$category);
        $response->assertRedirect('/login');
    }
    /** @test */
    public function authenticated_user_can_not_create_category_if_name_field_is_null()
    {
        $this->actingAs($this->admin);
        $category=$this->makeCategory(['name' => null]);
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertSessionHasErrors(['name']);
    }
    /** @test */
    public function authenticated_user_can_not_create_category_if_parent_id_not_exists()
    {
        $this->actingAs($this->admin);
        $category=$this->makeCategory(['parent_id' => self::INVALID_ID]);
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertSessionHasErrors(['parent_id']);
    }
    /** @test */
    public function authenticated_user_can_view_create_category_form()
    {
        $this->actingAs($this->admin);
        $response = $this->get($this->getCreateCategoryViewRoute());
        $response->assertViewIs('categories.create');
    }
    /** @test */
    public function authenticated_user_can_see_name_required_text_if_validate_error()
    {
        $this->actingAs($this->admin);
        $category=$this->makeCategory(['name' => null]);
        $response = $this->from($this->getCreateCategoryViewRoute())->post($this->getCreateCategoryRoute(), $category);
        $response->assertRedirect($this->getCreateCategoryViewRoute());
    }
    /** @test */
    public function unauthenticated_user_can_not_see_create_category_form_view()
    {
        $response = $this->get($this->getCreateCategoryViewRoute());
        $response->assertRedirect('/login');
    }
    protected function makeCategory(array $overrides = [])
    {
        return Category::factory()->make($overrides)->toArray();
    }
    public function getCreateCategoryRoute(){ 
        return route('categories.store');
    }
    public function getCreateCategoryViewRoute(){
        return route('categories.create');
    }

}
