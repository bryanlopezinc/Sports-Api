<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\CoachesCareersCacheRepository;
use Module\Football\Contracts\Cache\CoachesCareesHistoryCacheInterface;

final class CoachesCareersCacheServiceProvider extends Provider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(CoachesCareesHistoryCacheInterface::class, function ($app) {
            return new CoachesCareersCacheRepository($app['cache']->store());
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
            CoachesCareesHistoryCacheInterface::class
        ];
    }
}
