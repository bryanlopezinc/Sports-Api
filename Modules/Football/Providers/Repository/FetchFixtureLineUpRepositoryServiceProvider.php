<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Clients\ApiSports\V3\FetchFixtureLineUpHttpClient;
use Module\Football\Contracts\Repositories\FetchFixtureLineUpRepositoryInterface;

class FetchFixtureLineUpRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchFixtureLineUpRepositoryInterface::class, fn () => app(FetchFixtureLineUpHttpClient::class));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchFixtureLineUpRepositoryInterface::class
        ];
    }
}
