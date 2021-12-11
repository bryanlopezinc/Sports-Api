<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\User\Predictions\Football\Contracts\StoreUserPredictionRepositoryInterface;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;

class PredictionRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $repository = new PredictionsRepository;

        $this->app->bind(StoreUserPredictionRepositoryInterface::class, fn () => $repository);
        $this->app->bind(FetchFixturePredictionsRepositoryInterface::class, fn () => $repository);
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
            FetchFixturePredictionsRepositoryInterface::class
        ];
    }
}
