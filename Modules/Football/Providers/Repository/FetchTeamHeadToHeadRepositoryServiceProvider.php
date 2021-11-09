<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Clients\ApiSports\V3\FetchTeamHeadToHeadHttpClient;
use Module\Football\Contracts\Repositories\FetchTeamHeadToHeadRepositoryInterface;

class FetchTeamHeadToHeadRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchTeamHeadToHeadRepositoryInterface::class, fn () => app(FetchTeamHeadToHeadHttpClient::class));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchTeamHeadToHeadRepositoryInterface::class
        ];
    }
}
