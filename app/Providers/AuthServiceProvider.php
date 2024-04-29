<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

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
        $this->registerPolicies();

        Gate::define('access-admin', function ($user) {
            return $user->role === 'Admin';
        });

        Gate::define('access-ugyfel', function ($user) {
            return $user->role === 'Ugyfel';
        });

        Gate::define('access-uzletkoto', function ($user) {
            return $user->role === 'Uzletkoto';
        });

        Gate::define('access-admin-or-uzletkoto', function ($user) {
            return in_array($user->role, ['Admin', 'Uzletkoto']);
        });

    }

    public function toResponse($request)
    {
        return redirect(RouteServiceProvider::redirectTo());
    }
}
