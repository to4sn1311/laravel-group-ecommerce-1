<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetListCategoryTest extends TestCase
{
    const INVALID_ID=-1;

    /** @test */
    public function authorized_user_can_get_all_category()
    {
        $this->actingAs($this->createAdmin());
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('categories.index');
    }

    /** @test */
    public function unauthorized_user_can_get_all_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function unauthenticated_user_can_get_all_category()
    {
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertRedirect('/login');
    }

    protected function createAdmin()
    {
        return User::factory()->admin()->create();
    }

    private function createCategory(): void
    {
        Category::factory()->create();
    }

    private function getCategoryIndex()
    {
        return $this->get(route('categories.index'));
    }
}
