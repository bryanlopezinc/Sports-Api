<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\LeaguesFixturesByDateCacheRepository;
use Module\Football\Contracts\Cache\LeaguesFixturesByDateCacheInterface;

final class LeaguesFixturesByDateCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(LeaguesFixturesByDateCacheInterface::class, function ($app) {
            return new LeaguesFixturesByDateCacheRepository($app['cache']->store());
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
            LeaguesFixturesByDateCacheInterface::class,
        ];
    }
}
