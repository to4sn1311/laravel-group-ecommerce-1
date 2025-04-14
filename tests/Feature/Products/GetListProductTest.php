<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetListProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $adminUser;
    protected $product;
    protected $categories;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCategories();
        $this->setUpProduct();
    }

    protected function setUpCategories()
    {
        $this->categories = Category::factory()->count(3)->create();
    }

    protected function setUpProduct()
    {
        $this->product = Product::factory()->create();
        $this->product->categories()->attach($this->categories->pluck('id')->toArray());
    }

    protected function getListProductRoute()
    {
        return route('products.index');
    }

    #[Test]
    protected function user_can_get_all_product()
    {
        $response = $this->get($this->getListProductRoute());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('products.index');
        $response->assertJsonFragment([
            'id' => $this->product->id,
            'name' => $this->product->name,
        ]);
    }
}
