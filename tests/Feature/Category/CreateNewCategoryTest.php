<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateNewCategoryTest extends TestCase
{

    const INVALID_ID=-1;
    
    #[Test]
    public function authenticated_user_can_view_create_category_form()
    {
        $this->actingAs($this->createAdmin());
        $response = $this->get($this->getCreateCategoryViewRoute());
        $response->assertViewIs('categories.create');
    }

    #[Test]
    public function unauthenticated_user_can_not_see_create_category_form()
    {
        $response = $this->get($this->getCreateCategoryViewRoute());
        $response->assertRedirect('/login');
    }

    #[Test]
    public function authorized_user_can_new_category()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->makeCategory();
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('categories', $category);
    }

    #[Test]
    public function unauthorized_user_cannot_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category=$this->makeCategory();
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    #[Test]
    public function unauthenticated_user_can_not_create_category()
    {
        $category=$this->makeCategory();
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_user_can_not_create_category_if_name_field_is_null()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->makeCategory(['name' => null]);
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertSessionHasErrors(['name']);
    }

    #[Test]
    public function authenticated_user_can_not_create_category_if_parent_id_not_exists()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->makeCategory(['parent_id' => self::INVALID_ID]);
        $response = $this->post($this->getCreateCategoryRoute(), $category);
        $response->assertSessionHasErrors(['parent_id']);
    }

    #[Test]
    public function authenticated_user_can_see_name_required_text_if_validate_error()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->makeCategory(['name' => null]);
        $response = $this->from($this->getCreateCategoryViewRoute())->post($this->getCreateCategoryRoute(), $category);
        $response->assertRedirect($this->getCreateCategoryViewRoute());
    }
    
    protected function makeCategory(array $overrides = [])
    {
        return Category::factory()->make($overrides)->toArray();
    }

    protected function createAdmin()
    {
        return User::factory()->admin()->create();
    }

    public function getCreateCategoryRoute()
    {
        return route('categories.store');
    }

    public function getCreateCategoryViewRoute()
    {
        return route('categories.create');
    }
}
