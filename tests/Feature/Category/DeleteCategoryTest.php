<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    const INVALID_ID=-1;
   
    #[Test]
    public function authorized_user_can_delete_category()
    {
        $this->actingAs($this->createAdmin());
        $category = $this->createCategory();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    #[Test]
    public function unauthorized_user_can_not_delete_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = $this->createCategory();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    #[Test]
    public function unauthenticated_user_can_not_delete_category()
    {
        $category = $this->createCategory();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_user_can_not_delete_category_if_not_found_id()
    {
        $this->actingAs($this->createAdmin());
        $category_id = self::INVALID_ID;
        $response = $this->delete($this->getDeleteCategoryRoute($category_id));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    protected function createCategory()
    {
        return Category::factory()->create();
    }

    public function getDeleteCategoryRoute($id)
    {
        return route('categories.destroy', $id);
    }

    protected function createAdmin()
    {
        return User::factory()->admin()->create();
    }
}
