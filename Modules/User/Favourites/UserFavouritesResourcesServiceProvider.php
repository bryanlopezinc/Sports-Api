<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\User\Favourites\Clients\FetchfavouritesHttpClient;

class UserFavouritesResourcesServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchUserFavouritesResourcesInterface::class, fn () => app(FetchfavouritesHttpClient::class));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchUserFavouritesResourcesInterface::class,
        ];
    }
}
