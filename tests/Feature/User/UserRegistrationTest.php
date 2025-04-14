<?php

namespace Tests\Feature\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create the User role that will be assigned to new registrations
        Role::create(['name' => 'User', 'description' => 'Regular user role']);
    }

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_with_valid_deha_soft_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@deha-soft.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@deha-soft.com',
        ]);
        
        // Check if the user has been assigned the 'User' role
        $user = User::where('email', 'test@deha-soft.com')->first();
        $this->assertTrue($user->hasRole('User'));
        
        // Check if user is redirected to client index page
        $response->assertRedirect(route('client.index', absolute: false));
    }

    public function test_registration_fails_with_non_deha_soft_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_registration_fails_with_invalid_email_format()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_fails_with_password_mismatch()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@deha-soft.com',
            'password' => 'password',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'email' => 'test@deha-soft.com',
        ]);
    }

    public function test_registration_fails_with_missing_required_fields()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }
}
