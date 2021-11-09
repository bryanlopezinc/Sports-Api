<?php

declare(strict_types=1);

namespace Module\User\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\User\Clients\Favourites\UserfavouritesHttpClient;
use Module\User\Contracts\FetchUserFavouritesResourcesRepositoryInterface;

class UserFavouritesResourcesServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchUserFavouritesResourcesRepositoryInterface::class, fn () => new UserfavouritesHttpClient());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchUserFavouritesResourcesRepositoryInterface::class,
        ];
    }
}
