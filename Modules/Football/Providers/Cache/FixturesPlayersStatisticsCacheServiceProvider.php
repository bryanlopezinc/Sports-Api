<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\FixturesPlayersStatisticsCacheRepository;
use Module\Football\Contracts\Cache\FixturesPlayersStatisticsCacheInterface;

final class FixturesPlayersStatisticsCacheServiceProvider extends Provider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(FixturesPlayersStatisticsCacheInterface::class, function ($app) {
            return new FixturesPlayersStatisticsCacheRepository($app['cache']->store());
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
            FixturesPlayersStatisticsCacheInterface::class
        ];
    }
}
