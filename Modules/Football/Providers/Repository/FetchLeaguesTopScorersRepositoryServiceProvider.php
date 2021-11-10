<?php

declare(strict_types=1);

namespace Module\Football\Providers\Repository;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Module\Football\Clients\ApiSports\V3\FetchLeagueTopScorersHttpClient;
use Module\Football\Contracts\Repositories\FetchLeagueTopScorersRepositoryInterface;

class FetchLeaguesTopScorersRepositoryServiceProvider extends Provider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->app->bind(FetchLeagueTopScorersRepositoryInterface::class, fn () => app(FetchLeagueTopScorersHttpClient::class));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return [
            FetchLeagueTopScorersRepositoryInterface::class
        ];
    }
}
