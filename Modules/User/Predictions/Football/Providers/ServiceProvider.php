<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\User\Predictions\Football\PredictionsRepository;
use Module\User\Predictions\Football\Cache\UsersPredictionsCacheRepository;
use Module\User\Predictions\Football\Cache\FixturePredictionsCacheRepository;
use Module\User\Predictions\Football\Contracts\StoreUserPredictionRepositoryInterface;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;

class ServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(StoreUserPredictionRepositoryInterface::class, fn () => new PredictionsRepository);

        $this->app->bind(FetchFixturePredictionsRepositoryInterface::class, function ($app) {
            return new UsersPredictionsCacheRepository(
                new FixturePredictionsCacheRepository(new PredictionsRepository, $app['cache']->store()),
                $app['cache']->store()
            );
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
            StoreUserPredictionRepositoryInterface::class,
            FetchFixturePredictionsRepositoryInterface::class,
        ];
    }
}
