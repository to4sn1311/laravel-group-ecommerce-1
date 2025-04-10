<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditCategoryTest extends TestCase
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
    
    public function getEditCategoryRoute($id){ 
        return route('categories.update', $id);
    }
    public function getEditCategoryViewRoute($id){
        return route('categories.edit',$id);
    }
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => fake()->name(),
            'parent_id' => null,
        ], $overrides);
    }

      /** @test */
    public function authorized_user_can_edit_catogery()
    {
        $this->actingAs($this->admin);
        $category=Category::factory()->create();
        $dataUpdate = $this->validData();
        $response = $this->put($this->getEditCategoryRoute($category->id),$dataUpdate);
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories',$dataUpdate);
    }
       /** @test */
    public function unauthorized_user_can_not_edit_catogery()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category=Category::factory()->create();
        $dataUpdate = $this->validData();
        $response = $this->put($this->getEditCategoryRoute($category->id),$dataUpdate);
        $response->assertStatus(403);
    }
            /** @test */
    public function unauthenticated_user_can_not_edit_category()
    {
        $category=Category::factory()->create();
        $dataUpdate = $this->validData();
        $response = $this->put($this->getEditCategoryRoute($category->id),$dataUpdate);
        $response->assertRedirect('/login');
    }
        /** @test */
    public function authenticated_user_can_not_edit_category_if_name_field_is_null()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->put($this->getEditCategoryRoute($category->id), $data);
        $response->assertSessionHasErrors(['name']);
    }
        /** @test */
    public function authenticated_user_can_not_edit_category_if_parent_id_not_exists()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $data=[
            'name'=>$category->name,
            'parent_id'=>-1
        ];
        $response = $this->put($this->getEditCategoryRoute($category->id), $data);
        $response->assertSessionHasErrors(['parent_id']);
    }
        /** @test */

    public function authenticated_user_can_view_edit_category_form()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $response = $this->get($this->getEditCategoryViewRoute($category->id));
        $response->assertViewIs('categories.edit');
    }
                /** @test */

    public function authenticated_user_can_see_name_required_text_if_validate_error()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->from($this->getEditCategoryViewRoute($category->id))->put($this->getEditCategoryRoute($category->id), $data);
        $response->assertRedirect($this->getEditCategoryViewRoute($category->id));
    }
            /** @test */
    public function authenticated_user_can_see_parent_id_not_exists_text_if_validate_error()
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $data=[
            'name'=>$category->name,
            'parent_id'=>-1
        ];
        $response = $this->from($this->getEditCategoryViewRoute($category->id))->put($this->getEditCategoryRoute($category->id), $data);
        $response->assertRedirect($this->getEditCategoryViewRoute($category->id));
    }
            /** @test */
    public function unauthenticated_user_can_not_see_edit_category_form_view()
    {
        $category = Category::factory()->create();
        $response = $this->get($this->getEditCategoryViewRoute($category->id));
        $response->assertRedirect('/login');
    }
}
