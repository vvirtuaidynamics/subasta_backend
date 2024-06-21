<?php

namespace App\Providers;

use App\Http\Api\Base\BaseRepository;
use App\Http\Api\Base\BaseRepositoryInterface;
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
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
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
