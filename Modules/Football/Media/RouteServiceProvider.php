<?php

declare(strict_types=1);

namespace Module\Football\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();

            //$this->mapWebRoutes();
        });
    }

    protected function mapApiRoutes(): void
    {
        Route::domain(env('PHOTOS_URL'))->group(base_path('Modules\Football\Media\routes.php'));
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
