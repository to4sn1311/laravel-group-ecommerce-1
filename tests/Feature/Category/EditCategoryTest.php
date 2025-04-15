<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{
    const INVALID_ID=-1;

    #[Test]
    public function authorized_user_can_edit_category()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->createCategory();
        $dataUpdate = $this->validData();
        $response = $this->put($this->updateCategoryRoute($category->id), $dataUpdate);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('categories', $dataUpdate);
    }

    #[Test]
    public function unauthorized_user_can_not_edit_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category=$this->createCategory();
        $dataUpdate = $this->validData();
        $response = $this->put($this->updateCategoryRoute($category->id), $dataUpdate);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    #[Test]
    public function unauthenticated_user_can_not_edit_category()
    {
        $category=$this->createCategory();
        $dataUpdate = $this->validData();
        $response = $this->put($this->updateCategoryRoute($category->id), $dataUpdate);
        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_user_can_not_edit_category_if_name_field_is_null()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->createCategory();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->put($this->updateCategoryRoute($category->id), $data);
        $response->assertSessionHasErrors(['name']);
    }

    #[Test]
    public function authenticated_user_can_not_edit_category_if_parent_id_not_exists()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->createCategory();
        $data=[
            'name'=>$category->name,
            'parent_id'=>self::INVALID_ID
        ];
        $response = $this->put($this->updateCategoryRoute($category->id), $data);
        $response->assertSessionHasErrors(['parent_id']);
    }

    #[Test]
    public function authenticated_user_can_view_edit_category_form()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->createCategory();
        $response = $this->get($this->editCategoryRoute($category->id));
        $response->assertViewIs('categories.edit');
    }

    #[Test]
    public function authenticated_user_can_see_name_required_text_if_validate_error()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->createCategory();
        $data=[
            'name'=>null,
            'parent_id'=>$category->parent_id
        ];
        $response = $this->from($this->editCategoryRoute($category->id))->put($this->updateCategoryRoute($category->id), $data);
        $response->assertRedirect($this->editCategoryRoute($category->id));
    }

    #[Test]
    public function authenticated_user_can_see_parent_id_not_exists_text_if_validate_error()
    {
        $this->actingAs($this->createAdmin());
        $category=$this->createCategory();
        $data=[
            'name'=>$category->name,
            'parent_id'=>self::INVALID_ID
        ];
        $response = $this->from($this->editCategoryRoute($category->id))->put($this->updateCategoryRoute($category->id), $data);
        $response->assertRedirect($this->editCategoryRoute($category->id));
    }

    #[Test]
    public function unauthenticated_user_can_not_see_edit_category_form_view()
    {
        $category=$this->createCategory();
        $response = $this->get($this->editCategoryRoute($category->id));
        $response->assertRedirect('/login');
    }

    protected function createAdmin()
    {
        return User::factory()->admin()->create();
    }

    public function updateCategoryRoute($id)
    {
        return route('categories.update', $id);
    }

    public function editCategoryRoute($id)
    {
        return route('categories.edit', $id);
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
}
