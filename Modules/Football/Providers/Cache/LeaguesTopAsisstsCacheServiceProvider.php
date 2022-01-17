<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\LeaguesTopAsisstsCacheRepository;
use Module\Football\Contracts\Cache\LeaguesTopAssistsCacheInterface;

final class LeaguesTopAsisstsCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(LeaguesTopAssistsCacheInterface::class, function ($app) {
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
            LeaguesTopAssistsCacheInterface::class,
        ];
    }
}
