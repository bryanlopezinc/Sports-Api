<?php

declare(strict_types=1);

namespace Module\User\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\User\Repository\UserFavouritesRepository;
use Module\User\Contracts\CreateUserFavouriteRepositoryInterface;
use Module\User\Contracts\FetchUserFavouritesRepositoryInterface;

class UserFavouritesRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(CreateUserFavouriteRepositoryInterface::class, fn() => new UserFavouritesRepository);
        $this->app->bind(FetchUserFavouritesRepositoryInterface::class, fn () => new UserFavouritesRepository);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            CreateUserFavouriteRepositoryInterface::class,
            FetchUserFavouritesRepositoryInterface::class
        ];
    }
}
