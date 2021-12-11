<?php

declare(strict_types=1);

use App\Http\Middleware;
use Module\User\Routes\Config;
use Module\User\Http\Controllers;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Route;

Route::middleware(Middleware\HandleDbTransactionsMiddleware::class)
    ->group(function () {

        Route::middleware(['auth:' . Config::GUARD])
            ->group(function () {
                Route::post('favourites/football/team', Controllers\AddFootballTeamToUserfavouritesController::class)->name(RouteName::ADD_FOOTBALL_TEAM_TO_FAVOURITES);
                Route::post('favourites/football/league', Controllers\AddFootballLeagueToUserFavouritesController::class)->name(RouteName::ADD_FOOTBALL_LEAGUE_TO_FAVOURITES);
                Route::get('auth/favourites', Controllers\MyFavouritesController::class)->name(RouteName::AUTH_USER_FAVOURITES);
                Route::get('auth/profile', Controllers\MyProfileController::class)->name(RouteName::AUTH_USER_PROFILE);
            });

        Route::post('oauth/token', Controllers\LoginController::class)->name(RouteName::LOGIN);
        Route::post('create', Controllers\CreateUserController::class)->name(RouteName::CREATE);
        Route::get('favourites', Controllers\UserFavouritesController::class)->name(RouteName::FAVOURITES);
        Route::get('profile', Controllers\UserProfileController::class)->name(RouteName::PROFILE);
    });
