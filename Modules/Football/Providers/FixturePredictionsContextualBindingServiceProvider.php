<?php

declare(strict_types=1);

namespace Module\Football\Providers;

use Illuminate\Support\Facades\Cache;
use Module\Football\Services\FetchFixtureService;
use Illuminate\Support\ServiceProvider as Provider;
use Module\Football\Contracts\Cache\FixturesCacheInterface;
use Module\Football\Http\Controllers\FetchFixturePredictionsController;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;
use Module\Football\Repository\FixtureTeamsForFixturePredictionsResponse;

class FixturePredictionsContextualBindingServiceProvider extends Provider
{
    public function boot(): void
    {
        $this->app->addContextualBinding(FetchFixturePredictionsController::class, FetchFixtureService::class, function () {
            return new FetchFixtureService(
                new FixtureTeamsForFixturePredictionsResponse(app(FetchFixtureRepositoryInterface::class), Cache::store()),
                app(FixturesCacheInterface::class)
            );
        });
    }
}
