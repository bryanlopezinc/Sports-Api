<?php

declare(strict_types=1);

namespace Module\Football\Media;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Media\StreamGetContentsHttpClient;
use Module\Football\Media\FetchImageHttpClientInterface;

final class ServiceProvider extends Provider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(FetchImageHttpClientInterface::class, fn () => new StreamGetContentsHttpClient());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchImageHttpClientInterface::class
        ];
    }
}
