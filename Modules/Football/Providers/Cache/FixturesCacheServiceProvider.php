<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Module\Football\Cache\FixturesCacheRepository;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;

final class FixturesCacheServiceProvider extends Provider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(FixturesCacheRepository::class, function ($app) {
            return new FixturesCacheRepository($app['cache']->store());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FixturesCacheRepository::class
        ];
    }
}
