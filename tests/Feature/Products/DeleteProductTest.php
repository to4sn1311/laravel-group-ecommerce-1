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

class DeleteProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $adminUser;
    protected $product;
    protected $categories;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCategories();
        $this->setUpAdminWithPermissionCreateProduct();
        $this->setUpProduct();
    }

    protected function setUpAdminWithPermissionCreateProduct()
    {
        $adminRole = Role::create([
            'name' => 'adminProduct',
            'display_name' => 'Administrator Product',
            'description' => 'User with permission delete Product'
        ]);

        $createProductPermission = Permission::create([
            'name' => 'product-delete',
            'display_name' => 'Delete Products',
            'description' => 'Permission to delete products'
        ]);

        $adminRole->permissions()->attach($createProductPermission->id);

        $this->adminUser = User::factory()->create([
            'email' => 'adminProduct@example.com',
            'name' => 'Admin User Product'
        ]);

        $this->adminUser->roles()->attach($adminRole->id);
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

    protected function getDeleteRoute($id)
    {
        return route('products.destroy', $id);
    }

    #[Test]
    public function authenticated_user_can_delete_product(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete($this->getDeleteRoute($this->product->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $this->product->id]);
    }

    #[Test]
    public function unauthenticated_user_cannot_delete_product(): void
    {
        $response = $this->delete($this->getDeleteRoute($this->product->id));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('products', ['id' => $this->product->id]);
    }
}
