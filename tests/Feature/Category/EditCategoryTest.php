<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function getEditCategoryRoute($id){ 
        return route('categories.update', $id);
    }
    public function getEditCategoryViewRoute($id){
        return route('categories.edit',$id);
    }
      /** @test */
    public function authenticated_user_can_edit_catogery()
    {
        $this->actingAs(User::factory()->create());
        $category=Category::factory()->create();
        $response = $this->put($this->getEditCategoryRoute($category->id),$category->toArray());

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories',$category->toArray());
        $response->assertRedirect(route('categories.index'));
    }
            /** @test */
    public function unauthenticated_user_can_not_edit_category()
    {
        $category=Category::factory()->create();
        $response = $this->put($this->getEditCategoryRoute($category->id),$category->toArray());
        $response->assertRedirect('/login');
    }
        /** @test */
    public function authenticated_user_can_not_edit_category_if_name_field_is_null()
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->put($this->getEditCategoryRoute($category), $data);
        $response->assertSessionHasErrors(['name']);
    }
            /** @test */
    public function authenticated_user_can_not_edit_category_if_parent_id_not_exists()
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $data=[
            'name'=>$category->name,
            'parent_id'=>-1
        ];
        $response = $this->put($this->getEditCategoryRoute($category), $data);
        $response->assertSessionHasErrors(['parent_id']);
    }
                /** @test */

    public function authenticated_user_can_view_edit_category_form()
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $response = $this->get($this->getEditCategoryViewRoute($category),$category->toArray());
        $response->assertViewIs('categories.edit');
    }
                /** @test */

    public function authenticated_user_can_see_name_required_text_if_validate_error()
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->from($this->getEditCategoryViewRoute($category))->put($this->getEditCategoryRoute($category), $data);
        $response->assertRedirect($this->getEditCategoryViewRoute($category));
    }
            /** @test */
    public function authenticated_user_can_see_parent_id_not_exists_text_if_validate_error()
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $data=[
            'name'=>$category->name,
            'parent_id'=>-1
        ];
        $response = $this->from($this->getEditCategoryViewRoute($category))->put($this->getEditCategoryRoute($category), $data);
        $response->assertRedirect($this->getEditCategoryViewRoute($category));
    }
            /** @test */
    public function unauthenticated_user_can_not_see_edit_category_form_view()
    {
        $category = Category::factory()->create();
        $response = $this->get($this->getEditCategoryViewRoute($category->id),$category->toArray());
        $response->assertRedirect('/login');
    }
}
