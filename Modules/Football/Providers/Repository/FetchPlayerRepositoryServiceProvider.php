<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Module\Football\Cache\PlayersCacheRepository;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Clients\ApiSports\V3\FetchPlayerHttpClient;
use Module\Football\Contracts\Repositories\FetchPlayerRepositoryInterface;

class FetchPlayerRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchPlayerRepositoryInterface::class, function ($app) {
            return new PlayersCacheRepository($app['cache']->store(), new FetchPlayerHttpClient());
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
            FetchPlayerRepositoryInterface::class
        ];
    }
}
