<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\LeaguesTopAsisstsCacheRepository;

final class LeaguesTopAsisstsCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(LeaguesTopAsisstsCacheRepository::class, function ($app) {
            return new LeaguesTopAsisstsCacheRepository($app['cache']->store());
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
            LeaguesTopAsisstsCacheRepository::class,
        ];
    }
}
