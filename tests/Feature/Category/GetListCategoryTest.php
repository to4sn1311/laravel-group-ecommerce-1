<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetListCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     public function getListCategoryRoute(){
        return route('categories.index'); //name
     }
    /** @test */
    public function user_can_get_all_category()
    {
        $this->actingAs(User::factory()->create());
        $category =Category::factory()->make();
        $response = $this->get($this->getListCategoryRoute());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('categories.index');
    }
 
}
