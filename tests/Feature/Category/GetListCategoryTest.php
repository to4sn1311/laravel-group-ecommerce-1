<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetListCategoryTest extends TestCase
{
    protected $admin;
    
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo quyền
        $manageC = Permission::firstOrCreate(['name' => 'category-list']);
        $manageC1 = Permission::firstOrCreate(['name' => 'category-create']);
        $manageC2 = Permission::firstOrCreate(['name' => 'category-edit']);
        $manageC3 = Permission::firstOrCreate(['name' => 'category-delete']);
        // Tạo vai trò
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Gán quyền cho vai trò Admin (chỉ thêm nếu chưa có)
        $adminRole->permissions()->syncWithoutDetaching([$manageC->id, $manageC1->id, $manageC2->id, $manageC3->id]);

        // Tạo tài khoản admin (nếu chưa có)
        $this->admin = User::factory()->create();
        $this->admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }

    public function getListCategoryRoute(){
    return route('categories.index'); //name
    }
    /** @test */
    public function authorized_user_can_get_all_category()
    {
        $this->actingAs($this->admin);
        Category::factory()->create();
        $response = $this->get($this->getListCategoryRoute());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('categories.index');
    }
    /** @test */
    public function unauthorized_user_can_get_all_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);        
        Category::factory()->create();
        $response = $this->get($this->getListCategoryRoute());
        $response->assertStatus(403);
    } 
     /** @test */
     public function unauthenticated_user_can_get_all_category()
     {
         Category::factory()->create();
         $response = $this->get($this->getListCategoryRoute());
         $response->assertRedirect('/login');
     }
}
