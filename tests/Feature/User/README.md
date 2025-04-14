# User Feature Tests

This directory contains feature tests for the user functionality in the application.

## Test Files

1. **UserRegistrationTest.php** - Tests for user registration functionality
   - Validates that users can register with valid @deha-soft.com email addresses
   - Validates that registration fails with invalid email formats
   - Validates that new users are assigned the 'User' role

2. **UserAuthenticationTest.php** - Tests for user authentication
   - Tests login functionality
   - Tests that users with different roles are redirected to appropriate pages
   - Tests access control to admin dashboard

3. **UserManagementTest.php** - Tests for user management (CRUD operations)
   - Tests that admins can create, read, update, and delete users
   - Tests validation rules for user creation and updates
   - Tests that regular users cannot access user management

4. **UserRoleTest.php** - Tests for user role functionality
   - Tests role assignment during registration
   - Tests that admins can assign multiple roles to users
   - Tests permission-based access control

5. **UserProfileTest.php** - Tests for user profile functionality
   - Tests profile viewing and updating
   - Tests password updates
   - Tests account deletion

## Running the Tests

To run all user feature tests:

```bash
php artisan test tests/Feature/User
```

To run a specific test file:

```bash
php artisan test tests/Feature/User/UserRegistrationTest.php
```

## Test Coverage

These tests cover:

1. User registration with email validation (@deha-soft.com domain)
2. User role assignment (default 'User' role)
3. Authentication and authorization
4. User management (CRUD operations)
5. Role-based access control
6. Permission-based access control
7. User profile management

## Notes

- Some tests may be skipped if the functionality is not fully implemented or behaves differently than expected
- The tests assume that the 'User' role exists in the database
- The tests use the RefreshDatabase trait to ensure a clean database state for each test
