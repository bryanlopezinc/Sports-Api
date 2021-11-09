<?php

declare(strict_types=1);

namespace Module\User\Providers;

use Module\User\Repository\UserRepository;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\User\Contracts\CreateUserRepositoryInterface;
use Module\User\Contracts\FetchUsersRepositoryInterface;

class UserRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(CreateUserRepositoryInterface::class, fn () => new UserRepository());
        $this->app->bind(FetchUsersRepositoryInterface::class, fn () => new UserRepository());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            CreateUserRepositoryInterface::class,
            FetchUsersRepositoryInterface::class,
        ];
    }
}
