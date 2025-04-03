<?php

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;

test('user_can_get_all_category', function () {
    /*
    $user = User::factory()->make([
        'email' => 'admin@gmail.com',
        'password' => bcrypt('password'),  // Mật khẩu nếu cần
    ]);    
    $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
    $user->roles()->attach($adminRole); // Nếu dùng bảng trung gian role_user

    $this->actingAs($user);

    $cate =Category::factory()->create();
    $response = $this->get(route('categories.index') );
    $response->assertStatus(Response::HTTP_OK);
    $response->assertViewIs('categories.index');
    $response->assertSee($cate->name);
    */
});
