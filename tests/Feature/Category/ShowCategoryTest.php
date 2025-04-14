<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
{

    const INVALID_ID=-1;
  
    public function showCategoryViewRoute($id)
    {
        return route('categories.show', $id);
    }
    protected function createCategory()
    {
        return Category::factory()->create([
            'name' => 'Áo abcdefch',
            'parent_id'=>null
        ]);
    }

    /** @test */
    public function unauthenticated_user_can_not_show_category()
    {
        $category = $this->createCategory();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authorized_user_can_show_category()
    {
        $this->actingAs($this->createAdmin());
        $category = $this->createCategory();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertViewIs('categories.show');
    }
    
    /** @test */
    public function unthorized_user_can_not_show_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = $this->createCategory();
        $response = $this->get($this->showCategoryViewRoute($category->id));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    protected function createAdmin()
    {
        return User::factory()->admin()->create();
    }
}
