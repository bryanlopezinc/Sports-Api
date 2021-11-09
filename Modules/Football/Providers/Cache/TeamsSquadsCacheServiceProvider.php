<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use App\Utils\Config;
use Illuminate\Support\ServiceProvider as Provider;
use Module\Football\Cache\TeamSquadCacheRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Contracts\Cache\TeamsSquadsCacheInterface;

final class TeamsSquadsCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(TeamsSquadsCacheInterface::class, function ($app) {

            $store = $app->runningUnitTests() ? env('CACHE_DRIVER') : Config::get('football.cache.teamsSquad.driver');

            return new TeamSquadCacheRepository(
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
            TeamsSquadsCacheInterface::class,
        ];
    }
}
