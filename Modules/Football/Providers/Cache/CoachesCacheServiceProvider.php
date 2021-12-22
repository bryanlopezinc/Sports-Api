<?php

declare(strict_types=1);

namespace Module\Football\Providers\Cache;

use Module\Football\Cache\CoachesCacheRepository;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Contracts\Cache\CoachesCacheInterface;

final class CoachesCacheServiceProvider extends Provider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(CoachesCacheInterface::class, function ($app) {
            return new CoachesCacheRepository($app['cache']->store());
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
            CoachesCacheInterface::class
        ];
    }
}
