<?php

declare(strict_types=1);

namespace App\HashId;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(HashIdInterface::class, fn () => new HashId(env('HASH_ID_SALT'), 8));
    }
}
