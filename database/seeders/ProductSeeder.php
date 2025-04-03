<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('products')->insert([
            [
                'id' => 1,
                'name' => 'Áo Thun Nam',
                'description' => 'Áo thun nam thời trang, chất liệu cotton thoáng mát.',
                'price' => round(150000.00),
            ],
            [
                'id' => 2,
                'name' => 'Áo Sơ Mi Nữ',
                'description' => 'Áo sơ mi nữ thanh lịch, dễ phối đồ.',
                'price' => 200000,
            ],
            [
                'id' => 3,
                'name' => 'Áo Khoác Nam',
                'description' => 'Áo khoác nam chống gió, phù hợp với thời tiết lạnh.',
                'price' => 350000,
            ],
            [
                'id' => 4,
                'name' => 'Áo Thun Nữ',
                'description' => 'Áo thun nữ thời trang, phù hợp cho mọi dịp.',
                'price' => 180000,
            ]
        ]);
    }
}
