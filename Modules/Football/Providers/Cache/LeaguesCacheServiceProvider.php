<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use App\Utils\Config;
use Module\Football\Cache\LeaguesCacheRepository;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\LeaguesSeasonsCacheRepository;
use Module\Football\Contracts\Cache\LeaguesCacheInterface;

final class LeaguesCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(LeaguesCacheInterface::class, function ($app) {

            $store = $app->runningUnitTests() ? env('CACHE_DRIVER') : Config::get('football.cache.leagues.driver');

            $leaguSeasonsCache = new LeaguesSeasonsCacheRepository(
                $app['cache']->store(
                    $app->runningUnitTests() ? env('CACHE_DRIVER') : Config::get('football.cache.leaguesSeasons.driver')
                )
            );

            return new LeaguesCacheRepository($app['cache']->store($store), $leaguSeasonsCache);
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
            LeaguesCacheInterface::class,
        ];
    }
}
