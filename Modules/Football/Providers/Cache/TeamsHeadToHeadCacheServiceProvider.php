<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\TeamsHeadToHeadCacheRepository;

final class TeamsHeadToHeadCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->singleton(TeamsHeadToHeadCacheRepository::class, function ($app) {
            return new TeamsHeadToHeadCacheRepository($app['cache']->store());
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
            TeamsHeadToHeadCacheRepository::class,
        ];
    }
}
