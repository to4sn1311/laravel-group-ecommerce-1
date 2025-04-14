<?php

namespace Tests\Feature\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        $userRole = Role::create(['name' => 'User', 'description' => 'Regular user role']);
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Administrator role']);
        
        // Create a regular user
        $user = User::factory()->create([
            'email' => 'user@deha-soft.com',
        ]);
        $user->roles()->attach($userRole->id);
        
        // Create an admin user
        $admin = User::factory()->create([
            'email' => 'admin@deha-soft.com',
        ]);
        $admin->roles()->attach($adminRole->id);
    }

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $response = $this->post('/login', [
            'email' => 'user@deha-soft.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('client.index', absolute: false));
    }

    public function test_admin_users_are_redirected_to_dashboard_after_login()
    {
        $response = $this->post('/login', [
            'email' => 'admin@deha-soft.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_cannot_authenticate_with_invalid_password()
    {
        $this->post('/login', [
            'email' => 'user@deha-soft.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_regular_users_cannot_access_admin_dashboard()
    {
        $user = User::where('email', 'user@deha-soft.com')->first();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertRedirect(route('client.index', absolute: false));
    }

    public function test_admin_users_can_access_admin_dashboard()
    {
        $admin = User::where('email', 'admin@deha-soft.com')->first();
        
        $response = $this->actingAs($admin)->get('/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_users_can_logout()
    {
        $user = User::where('email', 'user@deha-soft.com')->first();
        
        $this->actingAs($user);
        $this->assertAuthenticated();
        
        $response = $this->post('/logout');
        
        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
