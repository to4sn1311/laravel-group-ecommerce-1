<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_product')->insert([
            [
                'id' => 1,
                'product_id' => 1,
                'category_id' => 1,
            ],
            [
                'id' => 2,
                'product_id' => 1,
                'category_id' => 2,
            ],
            [
                'id' => 3,
                'product_id' => 2,
                'category_id' => 1,
            ],
            [
                'id' => 4,
                'product_id' => 2,
                'category_id' => 9,
            ],
            [
                'id' => 5,
                'product_id' => 3,
                'category_id' => 1,
            ],
            [
                'id' => 6,
                'product_id' => 3,
                'category_id' => 4,
            ],
            [
                'id' => 7,
                'product_id' => 4,
                'category_id' => 1,
            ],
            [
                'id' => 8,
                'product_id' => 4,
                'category_id' => 6,
            ],
        ]);
    }
}
