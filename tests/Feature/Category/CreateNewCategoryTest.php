<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateNewCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    public function authenticated_user_can_new_category()
    {
        $this->actingAs(User::factory()->create());
        $category=Category::factory()->make()->toArray();
        $response = $this->post($this->getCreateCategoryRoute(),$category);

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories',$category);
        $response->assertRedirect(route('categories.index'));
    }
    public function getCreateCategoryRoute(){ 
        return route('categories.store');
    }
    //ko dn k dc tao
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
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->make(['name' => null])->toArray();
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertSessionHasErrors(['name']);
    }
        /** @test */
    public function authenticated_user_can_not_create_category_if_parent_id_not_exists()
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->make(['parent_id' => -1])->toArray();
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertSessionHasErrors(['parent_id']);
    }
        /** @test */
    public function authenticated_user_can_view_create_category_form()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get($this->getCreateCategoryViewRoute());
        $response->assertViewIs('categories.create');
    }
            /** @test */

    public function authenticated_user_can_see_name_required_text_if_validate_error()
    {
        $this->actingAs(User::factory()->create());
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

    public function getCreateCategoryViewRoute(){
        return route('categories.create');
    }

}
