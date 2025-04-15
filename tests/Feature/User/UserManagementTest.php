<?php

namespace Tests\Feature\User;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $regularUser;
    protected $userRole;
    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create permissions
        $permissions = [
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'description' => ucfirst(str_replace('-', ' ', $permission))
            ]);
        }

        // Create roles
        $this->userRole = Role::create(['name' => 'User', 'description' => 'Regular user role']);
        $this->adminRole = Role::create(['name' => 'Admin', 'description' => 'Administrator role']);

        // Assign permissions to admin role
        $this->adminRole->permissions()->attach(Permission::all());

        // Create users
        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@deha-soft.com',
        ]);
        $this->admin->roles()->attach($this->adminRole->id);

        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@deha-soft.com',
        ]);
        $this->regularUser->roles()->attach($this->userRole->id);
    }

    public function test_admin_can_view_users_list()
    {
        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
        $response->assertViewHas('users');
    }

    public function test_admin_can_view_user_create_form()
    {
        $response = $this->actingAs($this->admin)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
        $response->assertViewHas('roles');
    }

    public function test_admin_can_create_new_user()
    {
        $response = $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'New Test User',
            'email' => 'newuser@deha-soft.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => [$this->userRole->id],
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New Test User',
            'email' => 'newuser@deha-soft.com',
        ]);

        $newUser = User::where('email', 'newuser@deha-soft.com')->first();
        $this->assertTrue($newUser->hasRole('User'));
    }

    public function test_admin_cannot_create_user_with_invalid_email()
    {
        $response = $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Invalid Email User',
            'email' => 'invalid@example.com', // Not a deha-soft.com email
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => [$this->userRole->id],
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', [
            'email' => 'invalid@example.com',
        ]);
    }

    public function test_admin_can_view_user_details()
    {
        $response = $this->actingAs($this->admin)->get(route('users.show', $this->regularUser->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user');
    }

    public function test_admin_can_view_user_edit_form()
    {
        $response = $this->actingAs($this->admin)->get(route('users.edit', $this->regularUser->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas(['user', 'roles', 'userRoles']);
    }

    public function test_admin_can_update_user()
    {
        $response = $this->actingAs($this->admin)->put(route('users.update', $this->regularUser->id), [
            'name' => 'Updated User Name',
            'email' => 'updated@deha-soft.com',
            'roles' => [$this->userRole->id],
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->regularUser->id,
            'name' => 'Updated User Name',
            'email' => 'updated@deha-soft.com',
        ]);
    }

    public function test_admin_can_update_user_password()
    {
        $response = $this->actingAs($this->admin)->put(route('users.update', $this->regularUser->id), [
            'name' => $this->regularUser->name,
            'email' => $this->regularUser->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'roles' => [$this->userRole->id],
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        // Log out admin and try to log in as the user with the new password
        $this->post('/logout');

        $loginResponse = $this->post('/login', [
            'email' => $this->regularUser->email,
            'password' => 'newpassword',
        ]);

        $this->assertAuthenticated();
    }

    public function test_admin_can_delete_user()
    {
        $userToDelete = User::factory()->create([
            'email' => 'todelete@deha-soft.com',
        ]);
        $userToDelete->roles()->attach($this->userRole->id);

        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $userToDelete->id));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_regular_user_cannot_access_user_management()
    {
        $response = $this->actingAs($this->regularUser)->get(route('users.index'));

        // The middleware might redirect instead of returning 403
        $response->assertStatus(302);
    }
}
