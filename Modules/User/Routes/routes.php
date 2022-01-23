<?php

declare(strict_types=1);

use App\Http\Middleware;
use Module\User\Routes\Config;
use Module\User\Http\Controllers;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Route;
use Module\User\Favourites;

Route::middleware(Middleware\HandleDbTransactionsMiddleware::class)->group(function () {

    Route::middleware(['auth:' . Config::GUARD])->group(function () {
        Route::get('auth/profile', Controllers\MyProfileController::class)->name(RouteName::AUTH_USER_PROFILE);
        Route::get('auth/favourites', [Favourites\FetchFavouritesController::class, 'auth'])->name(RouteName::AUTH_USER_FAVOURITES);
        Route::get('favourites/fixtures', Favourites\FetchFixturesForUserFavouritesController::class)->name(RouteName::USER_FAVOURITES_FIXTURES);
    });

    Route::post('oauth/token', Controllers\LoginController::class)->name(RouteName::LOGIN);
    Route::post('create', Controllers\CreateUserController::class)->name(RouteName::CREATE);
    Route::get('profile', Controllers\UserProfileController::class)->name(RouteName::PROFILE);
    Route::get('favourites', [Favourites\FetchFavouritesController::class, 'forGuest'])->name(RouteName::FAVOURITES);
});
