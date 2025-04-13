<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class EditCategoryTest extends TestCase
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
    public function updateCategoryRoute($id){ 
        return route('categories.update', $id);
    }
    public function editCategoryRoute($id){
        return route('categories.edit',$id);
    }
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => fake()->name(),
            'parent_id' => null,
        ], $overrides);
    }
    protected function createCategory()
    {
        return Category::factory()->create();
    }
    /** @test */
    public function authorized_user_can_edit_category()
    {
        $this->actingAs($this->admin);
        $category=$this->createCategory();
        $dataUpdate = $this->validData();
        $response = $this->put($this->updateCategoryRoute($category->id),$dataUpdate);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('categories',$dataUpdate);
    }
    /** @test */
    public function unauthorized_user_can_not_edit_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category=$this->createCategory();
        $dataUpdate = $this->validData();
        $response = $this->put($this->updateCategoryRoute($category->id),$dataUpdate);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
    /** @test */
    public function unauthenticated_user_can_not_edit_category()
    {
        $category=$this->createCategory();
        $dataUpdate = $this->validData();
        $response = $this->put($this->updateCategoryRoute($category->id),$dataUpdate);
        $response->assertRedirect('/login');
    }
    /** @test */
    public function authenticated_user_can_not_edit_category_if_name_field_is_null()
    {
        $this->actingAs($this->admin);
        $category=$this->createCategory();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->put($this->updateCategoryRoute($category->id), $data);
        $response->assertSessionHasErrors(['name']);
    }
        /** @test */
    public function authenticated_user_can_not_edit_category_if_parent_id_not_exists()
    {
        $this->actingAs($this->admin);
        $category=$this->createCategory();
        $data=[
            'name'=>$category->name,
            'parent_id'=>self::INVALID_ID
        ];
        $response = $this->put($this->updateCategoryRoute($category->id), $data);
        $response->assertSessionHasErrors(['parent_id']);
    }
    /** @test */
    public function authenticated_user_can_view_edit_category_form()
    {
        $this->actingAs($this->admin);
        $category=$this->createCategory();
        $response = $this->get($this->editCategoryRoute($category->id));
        $response->assertViewIs('categories.edit');
    }
    /** @test */
    public function authenticated_user_can_see_name_required_text_if_validate_error()
    {
        $this->actingAs($this->admin);
        $category=$this->createCategory();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->from($this->editCategoryRoute($category->id))->put($this->updateCategoryRoute($category->id), $data);
        $response->assertRedirect($this->editCategoryRoute($category->id));
    }
    /** @test */
    public function authenticated_user_can_see_parent_id_not_exists_text_if_validate_error()
    {
        $this->actingAs($this->admin);
        $category=$this->createCategory();
        $data=[
            'name'=>$category->name,
            'parent_id'=>self::INVALID_ID
        ];
        $response = $this->from($this->editCategoryRoute($category->id))->put($this->updateCategoryRoute($category->id), $data);
        $response->assertRedirect($this->editCategoryRoute($category->id));
    }
    /** @test */
    public function unauthenticated_user_can_not_see_edit_category_form_view()
    {
        $category=$this->createCategory();
        $response = $this->get($this->editCategoryRoute($category->id));
        $response->assertRedirect('/login');
    }
}
