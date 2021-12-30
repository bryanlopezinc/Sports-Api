<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\TeamSquadCacheRepository;
use Module\Football\Clients\ApiSports\V3\FetchTeamSquadHttpClient;
use Module\Football\Contracts\Repositories\FetchTeamSquadRepositoryInterface;

class FetchTeamSquadRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchTeamSquadRepositoryInterface::class, function ($app) {
            return new TeamSquadCacheRepository($app['cache']->store(), new FetchTeamSquadHttpClient);
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
            FetchTeamSquadRepositoryInterface::class
        ];
    }
}
