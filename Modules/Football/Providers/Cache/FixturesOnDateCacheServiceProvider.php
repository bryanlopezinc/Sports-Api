<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\FixturesByDateCacheRepository;
use Module\Football\Contracts\Cache\FixturesByDateCacheInterface;

final class FixturesOnDateCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(FixturesByDateCacheInterface::class, function ($app) {
            return new FixturesByDateCacheRepository($app['cache']->store());
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
            FixturesByDateCacheInterface::class
        ];
    }
}
