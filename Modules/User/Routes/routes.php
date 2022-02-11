<?php

declare(strict_types=1);

use App\Http\Middleware;
use App\Http\Middleware\Authenticate;
use Module\User\Http\Controllers;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Route;
use Module\User\Favourites;

Route::middleware(Middleware\HandleDbTransactionsMiddleware::class)->group(function () {

    Route::middleware([Authenticate::user()])->group(function () {
        Route::get('auth/profile', [Controllers\UserProfileController::class, 'auth'])->name(RouteName::AUTH_USER_PROFILE);
        Route::get('auth/favourites', [Favourites\FetchFavouritesController::class, 'auth'])->name(RouteName::AUTH_USER_FAVOURITES);
        Route::get('favourites/fixtures', Favourites\FetchFixturesForUserFavouritesController::class)->name(RouteName::USER_FAVOURITES_FIXTURES);
    });

    Route::post('oauth/token', Controllers\LoginController::class)->name(RouteName::LOGIN);
    Route::post('create', Controllers\CreateUserController::class)->name(RouteName::CREATE);
    Route::get('profile', [Controllers\UserProfileController::class, 'guest'])->name(RouteName::PROFILE);
    Route::get('favourites', [Favourites\FetchFavouritesController::class, 'forGuest'])->name(RouteName::FAVOURITES);

    Route::get('predictions', [Controllers\FetchUserPredictionsController::class, 'guest'])->name(RouteName::USER_PREDICtions);
    Route::get('auth/predictions', [Controllers\FetchUserPredictionsController::class, 'auth'])
        ->middleware(Authenticate::user())
        ->name(RouteName::AUTH_USER_PREDICtions);
});
