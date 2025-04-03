<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Danh mục cấp 1
          $categories = [
            ['name' => 'Áo Nam', 'parent_id' => null],
            ['name' => 'Áo Nữ', 'parent_id' => null],
            ['name' => 'Áo Trẻ Em', 'parent_id' => null],
        ];
        foreach ($categories as $category) {
            $id = DB::table('categories')->insertGetId($category);

            // Danh mục cấp 2
            DB::table('categories')->insert([
                ['name' => $category['name'] . ' Thể thao', 'parent_id' => $id],
                ['name' => $category['name'] . ' Công sở', 'parent_id' => $id],
                ['name' => $category['name'] . ' Streetwear', 'parent_id' => $id],
                ['name' => $category['name'] . ' Hoodie & Sweater', 'parent_id' => $id],
                ['name' => $category['name'] . ' Áo Polo', 'parent_id' => $id],
                ['name' => $category['name'] . ' Áo Sơ mi', 'parent_id' => $id],
                ['name' => $category['name'] . ' Áo Thun', 'parent_id' => $id],
                ['name' => $category['name'] . ' Áo Khoác', 'parent_id' => $id],
            ]);
        }
    }
}
