<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Clients\ApiSports\V3\FetchFixtureEventsClient;
use Module\Football\Contracts\Repositories\FetchFixtureEventsRepositoryInterface;

class FetchFixtureEventsRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchFixtureEventsRepositoryInterface::class, fn () => app(FetchFixtureEventsClient::class));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchFixtureEventsRepositoryInterface::class
        ];
    }
}
