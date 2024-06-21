<?php

namespace App\Http\Api\User;

use App\Http\Api\Base\BaseRepository;
use App\Http\Api\Base\BaseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(BaseRepository::class, UserRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
