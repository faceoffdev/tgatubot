<?php

namespace App\Framework\Providers;

use App\Framework\Guard\MessengerGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('messenger', function ($app, $name, array $config) {
            return new MessengerGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });
    }
}
