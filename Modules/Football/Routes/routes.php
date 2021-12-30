<?php

declare(strict_types=1);

use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Route;
use Module\Football\Http\Controllers;
use Module\Football\Http\Middleware as MW;
use App\HashId\ConvertHashedValuesToIntegerMiddleware as Convert;
use Module\Football\Http\Middleware\ConvertLeagueStandingTeamsMiddleware;

Route::prefix('football')->group(function () {

    Route::prefix('teams')->group(function () {
        Route::get('find', Controllers\FetchTeamConttroller::class)->name(Name::FETCH_TEAM)->middleware(Convert::keys('id'));
        Route::get('squad_list', Controllers\FetchTeamSquadController::class)->name(Name::FETCH_TEAM_SQUAD)->middleware(Convert::keys('id'));

        Route::get('head_to_head', Controllers\FetchTeamHeadToHeadController::class)
            ->name(Name::FETCH_TEAM_HEAD_TO_HEAD)
            ->middleware(Convert::keys('team_id_1', 'team_id_2'));
    });

    Route::prefix('leagues')->group(function () {
        Route::get('fixtures/date', Controllers\FetchLeagueFixturesByDateController::class)
            ->middleware(Convert::keys('league_id'))
            ->name(Name::FETCH_LEAGUE_FIXTURE_BY_DATE);

        Route::get('find', Controllers\FetchLeagueController::class)->name(Name::FETCH_LEAGUE)->middleware(Convert::keys('id'));

        Route::get('standing', Controllers\FetchLeagueStandingController::class)
            ->name(Name::FETCH_LEAGUE_STANDING)
            ->middleware([
                Convert::keys('league_id'),
                ConvertLeagueStandingTeamsMiddleware::class,
                Mw\EnsureLeagueHasStandingCoverageMiddleware::class
            ]);

        Route::get('top-scorers', Controllers\FetchLeagueTopScorersController::class)
            ->name(Name::FETCH_LEAGUE_TOP_SCORERS)
            ->middleware([Convert::keys('id'), Mw\CheckCoversLeagueTopScorersMiddleware::class]);

        Route::get('top-assists', Controllers\FetchLeagueTopAssistsController::class)
            ->name(Name::FETCH_LEAGUE_TOP_ASSISTS)
            ->middleware([Convert::keys('id'), Mw\CheckCoversLeagueTopAssistsMiddleware::class]);
    });

    Route::prefix('fixtures')->group(function () {
        Route::get('live', Controllers\FetchLiveFixturesController::class)->name(Name::FETCH_LIVE_FIXTURES);
        Route::get('date', Controllers\FetchFixturesByDateController::class)->name(Name::FETCH_FIXTURES_BY_DATE);

        Route::get('predictions', Controllers\FetchFixturePredictionsController::class)
            ->name(Name::FETCH_FIXTURE_PREDICTIONS)
            ->middleware(Convert::keys('id'));

        Route::get('players/statistics', Controllers\FetchFixturePlayersStatisticsController::class)
            ->middleware([Convert::keys('id'), MW\EnsureCoversPlayerStatisticsMiddleware::class])
            ->name(Name::FETCH_FIXTURES_PLAYERS_STAT);

        Route::get('find', Controllers\FetchFixtureController::class)
            ->name(Name::FETCH_FIXTURE)
            ->middleware([Convert::keys('id'), MW\SetFindFixtureResponseHeadersMiddleware::class]);

        Route::get('lineup', Controllers\FetchFixtureLineUpController::class)
            ->name(Name::FETCH_FIXTURE_LINEUP)
            ->middleware([Convert::keys('id'), MW\CheckCoversFixtureLineUpMiddleware::class]);

        Route::get('stats', Controllers\FetchFixtureStatisticsController::class)
            ->name(Name::FETCH_FIXTURE_STATS)
            ->middleware([Convert::keys('id'), MW\CheckCoversFixtureStatisticsMiddleware::class]);

        Route::get('events', Controllers\FetchFixtureEventsController::class)
            ->name(Name::FETCH_FIXTURE_EVENTS)
            ->middleware([Convert::keys('id'), MW\CheckCoversEventsMiddleware::class]);
    });

    Route::prefix('coachs')->group(function () {
        Route::get('find', Controllers\FetchCoachConttroller::class)->name(Name::FETCH_COACH)->middleware(Convert::keys('id'));

        Route::get('team_history', Controllers\FetchCoachCareerHistoryController::class)
            ->name(Name::FETCH_COACH_CAREER_HISTORY)
            ->middleware(Convert::keys('id'));
    });

    Route::prefix('players')->group(function () {
        Route::get('find', Controllers\FetchPlayerController::class)->name(Name::FETCH_PLAYER)->middleware(Convert::keys('id'));
    });
});
