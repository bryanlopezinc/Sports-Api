<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Providers;

use Illuminate\Support\ServiceProvider as Provider;

class MigrationsServiceProvider extends Provider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(base_path('Modules\Football\Prediction\Migrations'));
    }
}
