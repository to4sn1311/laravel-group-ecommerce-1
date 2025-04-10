<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Định nghĩa Gate để kiểm tra quyền truy cập vào admin dashboard
        Gate::define('access-admin', function (User $user) {
            // Kiểm tra nếu người dùng có nhiều hơn 1 role hoặc có role khác User
            return $user->roles->count() > 1 || !$user->hasRole('User');
        });

        // Định nghĩa Gate để kiểm tra quyền truy cập vào client area
        Gate::define('access-client', function (User $user) {
            // Tất cả người dùng đều có thể truy cập client area
            return true;
        });
    }
}
