<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Cache\CoachesCareersCacheRepository;
use Module\Football\Clients\ApiSports\V3\FetchCoachCareerHttpClient;
use Module\Football\Contracts\Repositories\FetchCoachCareerHistoryRepositoryInterface;

class FetchCoachCareerHistoryRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchCoachCareerHistoryRepositoryInterface::class, function ($app) {
            return new CoachesCareersCacheRepository($app['cache']->store(), new FetchCoachCareerHttpClient);
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
            FetchCoachCareerHistoryRepositoryInterface::class
        ];
    }
}
