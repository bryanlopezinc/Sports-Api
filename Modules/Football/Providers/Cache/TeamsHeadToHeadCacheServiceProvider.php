<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use App\Utils\Config;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\TeamsHeadToHeadCacheRepository;
use Module\Football\Contracts\Cache\TeamsHeadToHeadCacheInterface;

final class TeamsHeadToHeadCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(TeamsHeadToHeadCacheInterface::class, function ($app) {

            $store = $app->runningUnitTests() ? env('CACHE_DRIVER') : Config::get('football.cache.teamH2H.driver');

            return new TeamsHeadToHeadCacheRepository(
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
            TeamsHeadToHeadCacheInterface::class,
        ];
    }
}
