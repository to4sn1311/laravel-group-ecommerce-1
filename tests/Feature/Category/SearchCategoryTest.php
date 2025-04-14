<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class SearchCategoryTest extends TestCase
{
    const INVALID_ID=-1;

    /** @test */
    public function user_can_search_categories()
    {
        $this->actingAs($this->createAdmin());
        // Tạo danh mục mẫu
        $this->createCategories(['Áo Thun1', 'Áo Sơ Mi1']);

        // Gửi request tìm kiếm với từ khóa "Áo"
        $response = $this->getJson(route('categories.search', ['keyword' => 'Áo']));

        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'categories' => [['id', 'name']],
            'pagination'
        ]);
        // Kiểm tra danh sách categories không rỗng
        $this->assertGreaterThan(0, count($response['categories']));
    }

    /** @test */
    public function user_can_search_children_categories()
    {
        $this->actingAs($this->createAdmin());
        // Tạo danh mục cha và danh mục con
        $parent = Category::firstOrCreate(['name' => 'Áo test']);
        $this->createCategories(['Áo Vải', 'Áo Da'], $parent->id);
        // Gửi request tìm kiếm danh mục con của "Áo" với từ khóa "Áo"
        $response = $this->getJson(route('categories.search-children', [
            'parentId' => $parent->id,
            'keyword' => 'Áo'
        ]));

        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'categories' => [['id', 'name']],
            'pagination'
        ]);
        // Kiểm tra danh sách categories không rỗng
        $this->assertGreaterThan(0, count($response['categories']));         
    }

    protected function createAdmin()
    {
        return User::factory()->admin()->create();
    }

    protected function createCategories(array $names, $parentId = null)
    {
        foreach ($names as $name) {
            Category::firstOrCreate([
                'name' => $name,
                'parent_id' => $parentId
            ]);
        }
    }
}
