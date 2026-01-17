<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Thing::class => ThingPolicy::class,
        Place::class => PlacePolicy::class,  
    ];

    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->is_admin === true;
        });

        Gate::define('view-all-things', function ($user) {
            return $user->is_admin === true;
        });

        Gate::define('manage-places', function ($user) {
            return $user->is_admin === true;
        });
    }
}
