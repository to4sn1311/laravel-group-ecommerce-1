<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function getDeleteCategoryRoute($id)
    {
        return route('categories.destroy',$id);
    }
    /** @test */

    public function authenticated_user_can_delete_category()
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $this->assertDatabaseMissing('categories',$category->toArray());
        $response->assertStatus(302);
        $response->assertRedirect(route('categories.index'));
    }
    /** @test */
    public function unauthenticated_user_can_not_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->delete($this->getDeleteCategoryRoute($category->id));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_not_delete_category_if_not_found_id()
    {
        $this->actingAs(User::factory()->create());
        $category_id = -1;
        $response = $this->delete($this->getDeleteCategoryRoute($category_id));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
//createRequest-idrequi..
}
