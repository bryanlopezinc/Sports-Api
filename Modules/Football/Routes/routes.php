<?php

declare(strict_types=1);

use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Route;
use Module\Football\Http\Controllers;
use Module\Football\Http\Middleware as MW;

Route::prefix('football')->group(function () {

    Route::prefix('teams')->group(function () {
        Route::get('find', Controllers\FetchTeamConttroller::class)->name(Name::FETCH_TEAM);
        Route::get('head_to_head', Controllers\FetchTeamHeadToHeadController::class)->name(Name::FETCH_TEAM_HEAD_TO_HEAD);
        Route::get('squad_list', Controllers\FetchTeamSquadController::class)->name(Name::FETCH_TEAM_SQUAD);
    });

    Route::prefix('leagues')->group(function () {
        Route::get('fixtures/date', Controllers\FetchLeagueFixturesByDateController::class)->name(Name::FETCH_LEAGUE_FIXTURE_BY_DATE);
        Route::get('find', Controllers\FetchLeagueController::class)->name(Name::FETCH_LEAGUE);
        Route::get('standing', Controllers\FetchLeagueStandingController::class)->name(Name::FETCH_LEAGUE_STANDING);

        Route::get('top-scorers', Controllers\FetchLeagueTopScorersController::class)
            ->name(Name::FETCH_LEAGUE_TOP_SCORERS)
            ->middleware(Mw\CheckCoversLeagueTopScorersMiddleware::class);

        Route::get('top-assists', Controllers\FetchLeagueTopAssistsController::class)
            ->name(Name::FETCH_LEAGUE_TOP_ASSISTS)
            ->middleware(Mw\CheckCoversLeagueTopAssistsMiddleware::class);
    });

    Route::prefix('fixtures')->group(function () {
        Route::get('live', Controllers\FetchLiveFixturesController::class)->name(Name::FETCH_LIVE_FIXTURES);
        Route::get('date', Controllers\FetchFixturesByDateController::class)->name(Name::FETCH_FIXTURES_BY_DATE);

        Route::get('find', Controllers\FetchFixtureController::class)
            ->name(Name::FETCH_FIXTURE)
            ->middleware(MW\SetFindFixtureResponseHeadersMiddleware::class);

        Route::get('lineup', Controllers\FetchFixtureLineUpController::class)
            ->name(Name::FETCH_FIXTURE_LINEUP)
            ->middleware(MW\CheckCoversFixtureLineUpMiddleware::class);

        Route::get('stats', Controllers\FetchFixtureStatisticsController::class)
            ->name(Name::FETCH_FIXTURE_STATS)
            ->middleware(MW\CheckCoversFixtureStatisticsMiddleware::class);

        Route::get('events', Controllers\FetchFixtureEventsController::class)
            ->name(Name::FETCH_FIXTURE_EVENTS)
            ->middleware(MW\CheckCoversEventsMiddleware::class);
    });

    Route::prefix('coachs')->group(function () {
        Route::get('find', Controllers\FetchCoachConttroller::class)->name(Name::FETCH_COACH);
    });
});
