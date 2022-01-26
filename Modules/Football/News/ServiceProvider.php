<?php

namespace Module\Football\News;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as Provider;
use Module\Football\News\Contracts\FetchHeadlinesRepositoryInterface;
use Module\Football\News\Repositories\FetchHeadlinesCacheRepository;
use Module\Football\News\Repositories\FetchHeadlinesRepository;

class ServiceProvider extends Provider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(FetchHeadlinesRepositoryInterface::class, function ($app) {
            return new FetchHeadlinesCacheRepository($app['cache']->store(), new FetchHeadlinesRepository);
        });
    }

    public function provides()
    {
        return [
            FetchHeadlinesRepositoryInterface::class
        ];
    }
}
