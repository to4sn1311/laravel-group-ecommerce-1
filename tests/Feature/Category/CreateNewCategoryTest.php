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

class CreateNewCategoryTest extends TestCase
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
    
    //
    /** @test */
    public function authorized_user_can_new_category()
    {
        $this->actingAs($this->admin);
        $category=Category::factory()->make()->toArray();
        $response = $this->post($this->getCreateCategoryRoute(),$category);
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories',$category);
    }
        /** @test */
    public function unauthorized_user_cannot_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category=Category::factory()->make()->toArray();
        $response = $this->post($this->getCreateCategoryRoute(),$category);
        $response->assertStatus(403);
    }
        /** @test */
    public function unauthenticated_user_can_not_create_category()
    {
        $category=Category::factory()->make()->toArray();
        $response = $this->post($this->getCreateCategoryRoute(),$category);
        $response->assertRedirect('/login');
    }
    

        /** @test */
    public function authenticated_user_can_not_create_category_if_name_field_is_null()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->make(['name' => null])->toArray();
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertSessionHasErrors(['name']);
    }
        /** @test */
    public function authenticated_user_can_not_create_category_if_parent_id_not_exists()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->make(['parent_id' => -1])->toArray();
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
        $category = Category::factory()->make(['name' => null])->toArray();
        $response = $this->from($this->getCreateCategoryViewRoute())->post($this->getCreateCategoryRoute(), $category);
        $response->assertRedirect($this->getCreateCategoryViewRoute());
    }
            /** @test */
    public function unauthenticated_user_can_not_see_create_category_form_view()
    {
        $response = $this->get($this->getCreateCategoryViewRoute());
        $response->assertRedirect('/login');
    }

    public function getCreateCategoryRoute(){ 
        return route('categories.store');
    }
    public function getCreateCategoryViewRoute(){
        return route('categories.create');
    }

}
