<?php

declare(strict_types=1);

namespace Module\User\Providers;

use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Passport::routes(function (RouteRegistrar $router) {
            $router->forTransientTokens();
        });
    }
}
