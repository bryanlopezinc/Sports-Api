<?php

namespace Module\User\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            base_path('Modules/User/Config/config.php'),
            'user'
        );
    }
}
