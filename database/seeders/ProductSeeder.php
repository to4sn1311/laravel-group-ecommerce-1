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
                'name' => 'Áo Phông Nam Slim Fit Rib',
                'description' => 'Áo thun thể thao với khả năng thấm hút mồ hôi tốt, khử mùi hiệu quả sẽ giúp bạn luôn khô ráo và thoải mái trong suốt buổi tập. Kháng khuẩn tới 30 lần giặt, giúp bạn luôn được bảo vệ khỏi vi khuẩn gây hại.',
                'price' => round(150000.00),
            ],
            [
                'id' => 2,
                'name' => 'Áo Thun Thể Thao Nam Ba Lỗ Vai Chờm Can Vai',
                'description' => 'Áo thun thể thao với khả năng thấm hút mồ hôi tốt, khử mùi hiệu quả sẽ giúp bạn luôn khô ráo và thoải mái trong suốt buổi tập.Kháng khuẩn tới 30 lần giặt, giúp bạn luôn được bảo vệ khỏi vi khuẩn gây hại.',
                'price' => 200000,
            ],
            [
                'id' => 3,
                'name' => 'Áo Phao Nam 3S Siêu Nhẹ Tay Raglan',
                'description' => 'Trải nghiệm sự thoải mái siêu nhẹ - ấm áp cùng áo phao nam 3S. Áo có trọng lượng nhẹ, mặc dễ chịu mà vẫn có khả năng giữ ấm tốt nhờ cấu trúc vải và bông kẹp ba lớp.',
                'price' => 350000,
            ],
            [
                'id' => 4,
                'name' => 'Áo Khoác Nam Bomber Da',
                'description' => 'Áo khoác bomber da nam cao cấp, chất liệu da PU bền đẹp, lót polyester ấm áp. Cản gió hiệu quả, giữ ấm cơ thể trong những ngày lạnh. Thiết kế thời trang, phù hợp nhiều phong cách.',
                'price' => 180000,
            ]
        ]);
    }
}
