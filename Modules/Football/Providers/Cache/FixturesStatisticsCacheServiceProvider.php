<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use App\Utils\Config;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\FixturesStatisticsCacheRepository;
use Module\Football\Contracts\Cache\FixturesStatisticsCacheInterface;

final class FixturesStatisticsCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(FixturesStatisticsCacheInterface::class, function ($app) {

            $store = $app->runningUnitTests()  ? env('CACHE_DRIVER') : Config::get('football.cache.fixturesStatistics.driver');

            return new FixturesStatisticsCacheRepository(
                $app['cache']->store($store),
            );
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
            FixturesStatisticsCacheInterface::class
        ];
    }
}
