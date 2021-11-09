<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use App\Utils\Config;
use Module\Football\Cache\TeamsCacheRepository;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Contracts\Cache\TeamsCacheInterface;

final class TeamsCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(TeamsCacheInterface::class, function ($app) {

            $store = $app->runningUnitTests() ? env('CACHE_DRIVER') : Config::get('football.cache.teams.driver');

            return new TeamsCacheRepository(
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
            TeamsCacheInterface::class,
        ];
    }
}
