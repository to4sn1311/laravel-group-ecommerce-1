<?php

namespace Tests\Feature\User;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $regularUser;
    protected $userRole;
    protected $adminRole;
    protected $editorRole;

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
        $this->editorRole = Role::create(['name' => 'Editor', 'description' => 'Editor role']);

        // Assign permissions to admin role
        $this->adminRole->permissions()->attach(Permission::all());

        // Assign some permissions to editor role
        $editorPermissions = Permission::whereIn('name', ['user-list', 'role-list'])->get();
        $this->editorRole->permissions()->attach($editorPermissions);

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

    public function test_new_registered_users_are_assigned_user_role()
    {
        $response = $this->post('/register', [
            'name' => 'New Registered User',
            'email' => 'newuser@deha-soft.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'newuser@deha-soft.com')->first();
        $this->assertTrue($user->hasRole('User'));
        $this->assertEquals(1, $user->roles->count());
    }

    public function test_admin_can_assign_multiple_roles_to_user()
    {
        $response = $this->actingAs($this->admin)->put(route('users.update', $this->regularUser->id), [
            'name' => $this->regularUser->name,
            'email' => $this->regularUser->email,
            'roles' => [$this->userRole->id, $this->editorRole->id],
        ]);

        $response->assertRedirect(route('users.index'));

        $this->regularUser->refresh();
        $this->assertTrue($this->regularUser->hasRole('User'));
        $this->assertTrue($this->regularUser->hasRole('Editor'));
        $this->assertEquals(2, $this->regularUser->roles->count());
    }

    public function test_user_with_multiple_roles_can_access_admin_dashboard()
    {
        // Assign editor role to regular user
        $this->regularUser->roles()->attach($this->editorRole->id);

        $response = $this->actingAs($this->regularUser)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_user_with_only_user_role_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->regularUser)->get('/dashboard');

        $response->assertRedirect(route('client.index'));
    }

    public function test_user_permissions_are_based_on_roles()
    {
        // Create a user with editor role
        $editor = User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@deha-soft.com',
        ]);
        $editor->roles()->attach($this->editorRole->id);

        // Editor should have user-list permission
        $this->assertTrue($editor->hasPermission('user-list'));

        // Editor should not have user-create permission
        $this->assertFalse($editor->hasPermission('user-create'));

        // Admin should have all permissions
        $this->assertTrue($this->admin->hasPermission('user-list'));
        $this->assertTrue($this->admin->hasPermission('user-create'));
        $this->assertTrue($this->admin->hasPermission('user-edit'));
        $this->assertTrue($this->admin->hasPermission('user-delete'));

        // Regular user should not have any admin permissions
        $this->assertFalse($this->regularUser->hasPermission('user-list'));
        $this->assertFalse($this->regularUser->hasPermission('user-create'));
    }

    public function test_user_with_permission_can_access_protected_route()
    {
        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function test_user_without_permission_cannot_access_protected_route()
    {
        $response = $this->actingAs($this->regularUser)->get(route('users.index'));

        // The middleware might redirect instead of returning 403
        $response->assertStatus(302);
    }
}
