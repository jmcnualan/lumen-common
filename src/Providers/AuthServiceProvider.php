<?php

namespace Dmn\Cmn\Providers;

use Dmn\Cmn\User;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            $payload = JWTAuth::parseToken()->payload();
            return new User($payload);
        });
    }
}
