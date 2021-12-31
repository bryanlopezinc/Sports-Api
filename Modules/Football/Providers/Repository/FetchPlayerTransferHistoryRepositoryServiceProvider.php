<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\PlayersTransferHistoryCacheRepository;
use Module\Football\Clients\ApiSports\V3\FetchPlayerTransferHistoryHttpClient;
use Module\Football\Contracts\Repositories\FetchPlayerTransferHistoryRepositoryInterface;

class FetchPlayerTransferHistoryRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchPlayerTransferHistoryRepositoryInterface::class, function ($app) {
            return new PlayersTransferHistoryCacheRepository($app['cache']->store(), new FetchPlayerTransferHistoryHttpClient());
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
            FetchPlayerTransferHistoryRepositoryInterface::class
        ];
    }
}
