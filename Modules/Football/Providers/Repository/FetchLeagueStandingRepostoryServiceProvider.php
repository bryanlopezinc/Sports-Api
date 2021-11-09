<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Clients\ApiSports\V3\FetchLeagueTableHttpClient;
use Module\Football\Contracts\Repositories\FetchLeagueStandingRepositoryInterface;

class FetchLeagueStandingRepostoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchLeagueStandingRepositoryInterface::class, fn () => app(FetchLeagueTableHttpClient::class));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchLeagueStandingRepositoryInterface::class
        ];
    }
}
