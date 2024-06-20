<?php

namespace App\Providers;

use App\Models\User;
use DragonCode\Support\Helpers\Boolean;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(255);

        Gate::define('is_admin', function (User $user) {
            return (bool) $user->is_admin;
        });
    }
}
