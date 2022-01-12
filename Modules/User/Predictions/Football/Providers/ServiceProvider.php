<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\User\Predictions\Football\Cache\FixturePredictionsCacheRepository;
use Module\User\Predictions\Football\Contracts\StoreUserPredictionRepositoryInterface;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;
use Module\User\Predictions\Football\FixturePredictionsResultCacheRepository;
use Module\User\Predictions\Football\PredictionsRepository;

class ServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $repository = new PredictionsRepository;

        $this->app->bind(StoreUserPredictionRepositoryInterface::class, fn () => $repository);
        $this->app->bind(FetchFixturePredictionsRepositoryInterface::class, fn ($app) => new FixturePredictionsCacheRepository($repository, $app['cache']->store()));
        $this->app->bind(FixturePredictionsResultCacheRepository::class, fn ($app) => new FixturePredictionsResultCacheRepository($app['cache']->store()));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            StoreUserPredictionRepositoryInterface::class,
            FetchFixturePredictionsRepositoryInterface::class,
            FixturePredictionsResultCacheRepository::class
        ];
    }
}
