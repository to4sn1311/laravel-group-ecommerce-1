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

class UpdateProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $adminUser;
    protected $categories;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCategories();
        $this->setUpProduct();
        $this->setUpAdminWithPermissionCreateProduct();
    }

    protected function setUpAdminWithPermissionCreateProduct()
    {
        $adminRole = Role::create([
            'name' => 'adminProduct',
            'display_name' => 'Administrator Product',
            'description' => 'User with permission update Product'
        ]);

        $createProductPermission = Permission::create([
            'name' => 'product-edit',
            'display_name' => 'Update Products',
            'description' => 'Permission to update products'
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

    public function getUpdateProductRoute($id)
    {
        return route('products.update', $id);
    }

    #[Test]
    public function authenticated_user_can_update_product()
    {
        $this->actingAs($this->adminUser);
        $data = [
            'name' => 'Test Product',
            'description' => 'A test product',
            'price' => 99999,
            'categories' => $this->categories->pluck('id')->toArray(),
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'name' => 'Test Product',
            'price' => 99999,
        ]);

        $updatedProduct = Product::find($this->product->id);
        $this->assertCount(3, $updatedProduct->categories);
    }

    #[Test]
    public function unauthenticated_user_cannot_update_product()
    {
        $data = [
            'name' => 'Unauthorized Product',
            'price' => 99000,
            'category_ids' => $this->categories->pluck('id')->toArray(),
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('products', ['name' => 'Unauthorized Product']);
    }

    #[Test]
    public function authenticated_user_cannot_create_new_product_if_price_field_is_null()
    {
        $this->actingAs($this->adminUser);
        $data = [
            'name' => 'Test Product',
            'description' => 'A test product',
            'price' => null,
            'categories' => $this->categories->pluck('id')->toArray(),
        ];
        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);
        $response->assertSessionHasErrors(['price']);
        $this->assertDatabaseMissing('products', [
            'name' => 'Test Product',
            'description' => 'A test product',
        ]);
    }

    #[Test]
    public function authenticated_user_can_create_product_with_valid_categories()
    {
        $this->actingAs($this->adminUser);

        $validCategoryIds = $this->categories->pluck('id')->toArray();

        $data = [
            'name' => 'Valid Product',
            'description' => 'Test description',
            'price' => 100000,
            'categories' => $validCategoryIds,
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['name' => 'Valid Product']);
    }


    #[Test]
    public function authenticated_user_cannot_create_new_product_if_name_field_is_null()
    {
        $this->actingAs($this->adminUser);
        $data = [
            'name' => '',
            'description' => 'A test product',
            'price' => 99999,
            'categories' => $this->categories->pluck('id')->toArray(),
        ];
        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('products', [
            'description' => 'A test product',
            'price' => 99999,
        ]);
    }

    #[Test]
    public function authenticated_user_cannot_create_product_if_categories_is_not_array()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100000,
            'categories' => 123, // không phải mảng
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertSessionHasErrors(['categories']);
    }

    #[Test]
    public function authenticated_user_cannot_create_product_if_categories_has_invalid_ids()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100000,
            'categories' => [999999], // ID không tồn tại
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertSessionHasErrors(['categories.0']);
    }

    #[Test]
    public function authenticated_user_cannot_create_product_if_category_id_is_string()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'name' => 'Invalid Category String',
            'description' => 'Test desc',
            'price' => 12345,
            'categories' => ['not-a-valid-id'], // sai kiểu dữ liệu
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertSessionHasErrors(['categories.0']);
    }

    #[Test]
    public function authenticated_user_cannot_create_product_if_category_id_is_null()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'name' => 'Null Category',
            'description' => 'Test desc',
            'price' => 12345,
            'categories' => [null],
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertSessionHasErrors(['categories.0']);
    }
    #[Test]
    public function authenticated_user_cannot_create_product_if_category_id_is_empty_string()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'name' => 'Empty String Category',
            'description' => 'Test desc',
            'price' => 12345,
            'categories' => [''],
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertSessionHasErrors(['categories.0']);
    }

    #[Test]
    public function authenticated_user_cannot_update_product_if_image_is_invalid()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'name' => 'Invalid Image Product',
            'description' => 'Product with invalid image file',
            'price' => 12345,
            'categories' => $this->categories->pluck('id')->toArray(),
            'image' => \Illuminate\Http\UploadedFile::fake()->create('document.pdf', 100),
        ];

        $response = $this->put($this->getUpdateProductRoute($this->product->id), $data);

        $response->assertSessionHasErrors(['image']);

        $this->assertDatabaseMissing('products', [
            'name' => 'Invalid Image Product',
        ]);
    }
}
