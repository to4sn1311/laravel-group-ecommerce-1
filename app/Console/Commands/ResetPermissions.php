<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đặt lại tất cả quyền, vai trò và gán vai trò cho người dùng';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu đặt lại quyền và vai trò...');

        // Xóa dữ liệu hiện có trong các bảng
        $this->truncateTables();

        // Chạy seeders
        $this->info('Tạo permissions...');
        $this->runSeeder(PermissionSeeder::class);
        
        $this->info('Tạo roles...');
        $this->runSeeder(RoleSeeder::class);
        
        $this->info('Gán roles cho người dùng...');
        $this->runSeeder(UserRoleSeeder::class);

        $this->info('Đã đặt lại quyền và vai trò thành công!');

        return 0;
    }

    /**
     * Xóa dữ liệu trong các bảng liên quan
     */
    private function truncateTables()
    {
        // Tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // Xóa dữ liệu trong các bảng
        DB::table('permission_role')->truncate();
        DB::table('role_user')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // Bật lại kiểm tra khóa ngoại
        Schema::enableForeignKeyConstraints();

        $this->info('Đã xóa dữ liệu hiện có trong các bảng liên quan');
    }

    /**
     * Gọi một seeder
     */
    private function runSeeder($seeder)
    {
        $instance = app($seeder);
        $instance->run();
    }
}
