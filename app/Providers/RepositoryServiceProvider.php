<?php

namespace App\Providers;

use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Add more repository bindings here as you create them
        // $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        // $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
