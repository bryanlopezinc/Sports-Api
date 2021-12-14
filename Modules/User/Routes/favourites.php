<?php

declare(strict_types=1);

use App\Http\Middleware;
use Module\User\Favourites;
use Module\User\Routes\Config;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Route;

Route::middleware([Middleware\HandleDbTransactionsMiddleware::class])
    ->group(function () {

        Route::middleware('auth:' . Config::GUARD)
            ->group(function () {
                Route::post('favourites/football/team', Favourites\Football\Controllers\AddTeamTofavouritesController::class)->name(RouteName::ADD_FOOTBALL_TEAM_TO_FAVOURITES);
                Route::post('favourites/football/league', Favourites\Football\Controllers\AddLeagueToFavouritesController::class)->name(RouteName::ADD_FOOTBALL_LEAGUE_TO_FAVOURITES);
                Route::get('auth/favourites', Favourites\MyFavouritesController::class)->name(RouteName::AUTH_USER_FAVOURITES);
            });

        Route::get('favourites', Favourites\FetchFavouritesController::class)->name(RouteName::FAVOURITES);
    });
