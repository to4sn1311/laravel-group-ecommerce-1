<?php

namespace App\Providers;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
