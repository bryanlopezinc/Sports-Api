<?php

declare(strict_types=1);

namespace Module\User\Providers;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(base_path('Modules\User\Database\Migrations'));
    }
}
