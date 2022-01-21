<?php

declare(strict_types=1);

use App\HashId\ConvertHashedValuesToIntegerMiddleware as ConvertHashedId;
use App\Http\Middleware\HandleDbTransactionsMiddleware as TransactionMiddleware;
use Module\User\Routes\Config;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Route;
use Module\Football\Prediction\Controllers;
use Module\Football\Prediction\Middleware;

Route::prefix('fixtures')->group(function () {

    Route::post('predict', Controllers\PredictFixtureController::class)
        ->middleware([
            TransactionMiddleware::class,
            'auth:' . Config::GUARD,
            ConvertHashedId::keys('fixture_id'),
            Middleware\EnsureUserCanPredictFixtureMiddleware::class,
            Middleware\EnsureFixtureCanBePredictedMiddleware::class
        ])
        ->name(RouteName::PREDICT_FIXTURE);

    Route::get('predictions', Controllers\FetchFixturePredictionsController::class)
        ->name(RouteName::FIXTURE_PREDICTIONS)
        ->middleware(ConvertHashedId::keys('id'), 'cache.headers:max_age=600');
});
