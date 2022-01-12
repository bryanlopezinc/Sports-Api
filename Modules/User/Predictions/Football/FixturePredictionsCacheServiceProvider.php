<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;

class FixturePredictionsCacheServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FixturePredictionsResultCacheRepository::class, fn () => new FixturePredictionsResultCacheRepository(Cache::store()));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FixturePredictionsResultCacheRepository::class,
        ];
    }
}
