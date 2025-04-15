<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetListCategoryTest extends TestCase
{
    const INVALID_ID=-1;

    #[Test]
    public function authorized_user_can_get_all_categories()
    {
        $this->actingAs($this->createAdmin());
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('categories.index');
    }

    #[Test]
    public function unauthorized_user_can_not_get_all_categories()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->createCategory();
        $response = $this->getCategoryIndex();
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    #[Test]
    public function unauthenticated_user_can_get_all_categories()
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
