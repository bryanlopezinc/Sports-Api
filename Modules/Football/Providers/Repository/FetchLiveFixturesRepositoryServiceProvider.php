<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\LiveFixturesCacheRepository;
use Module\Football\Clients\ApiSports\V3\FetchLiveFixturesHttpClient;
use Module\Football\Contracts\Repositories\FetchLiveFixturesRepositoryInterface;

class FetchLiveFixturesRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchLiveFixturesRepositoryInterface::class, function ($app) {
            return new LiveFixturesCacheRepository($app['cache']->store(), new FetchLiveFixturesHttpClient);
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
            FetchLiveFixturesRepositoryInterface::class
        ];
    }
}
