<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\FixturesStatisticsCacheRepository;

final class FixturesStatisticsCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(FixturesStatisticsCacheRepository::class, function ($app) {
            return new FixturesStatisticsCacheRepository($app['cache']->store());
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
            FixturesStatisticsCacheRepository::class
        ];
    }
}
