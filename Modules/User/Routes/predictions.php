<?php

declare(strict_types=1);

use App\HashId\ConvertHashedValuesToIntegerMiddleware;
use App\Http\Middleware;
use Module\User\Routes\Config;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Route;
use Module\User\Predictions\Football;

Route::prefix('predictions')
    ->middleware([Middleware\HandleDbTransactionsMiddleware::class, 'auth:' . Config::GUARD])
    ->group(function () {

        Route::post('football/predict', Football\PredictFixtureController::class)
            ->middleware(ConvertHashedValuesToIntegerMiddleware::keys('fixture_id'))
            ->middleware(Football\EnsureFixtureCanBePredictedMiddleware::class)
            ->name(RouteName::PREDICT_FOOTBALL_FIXTURE);
    });
