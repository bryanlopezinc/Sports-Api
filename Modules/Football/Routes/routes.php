<?php

declare(strict_types=1);

use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Route;
use Module\Football\Http\Controllers;
use Module\Football\Http\Middleware as MW;
use App\HashId\ConvertHashedValuesToIntegerMiddleware as Convert;
use App\Http\Middleware\Authenticate;
use Module\Football\Http\Middleware\ConvertLeagueStandingTeamsMiddleware;
use App\Http\Middleware\HandleDbTransactionsMiddleware as TransactionMiddleware;
use Module\Football\Favourites\Controllers as FC;
use Module\Football\Prediction\Controllers as PC;
use Module\Football\Prediction\Middleware as PCM;
use Module\Football\Http\Middleware\EnsureFixtureExistsMiddleware;

//Teams Routes
Route::prefix('teams')->group(function () {
    Route::get('find', Controllers\FetchTeamConttroller::class)
        ->name(RouteName::FIND_TEAM)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=86400');

    Route::get('squad_list', Controllers\FetchTeamSquadController::class)
        ->name(RouteName::TEAM_SQUAD)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=604800');

    Route::get('head_to_head', Controllers\FetchTeamsHeadToHeadController::class)
        ->name(RouteName::TEAMS_H2H)
        ->middleware(Convert::keys('team_id_1', 'team_id_2'));
});

//Leagues Routes
Route::prefix('leagues')->group(function () {
    Route::get('fixtures/date', Controllers\FetchLeagueFixturesByDateController::class)
        ->middleware(Convert::keys('league_id'))
        ->name(RouteName::LEAGUE_FIXTURE_BY_DATE);

    Route::get('find', Controllers\FetchLeagueController::class)
        ->name(RouteName::FIND_LEAGUE)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=1800');

    Route::get('standing', Controllers\FetchLeagueStandingController::class)
        ->name(RouteName::LEAGUE_STANDING)
        ->middleware([
            Convert::keys('league_id'),
            ConvertLeagueStandingTeamsMiddleware::class,
            Mw\EnsureLeagueHasStandingCoverageMiddleware::class
        ]);

    Route::get('top-scorers', Controllers\FetchLeagueTopScorersController::class)
        ->name(RouteName::LEAGUE_TOP_SCORERS)
        ->middleware([Convert::keys('id'), Mw\CheckCoversLeagueTopScorersMiddleware::class]);

    Route::get('top-assists', Controllers\FetchLeagueTopAssistsController::class)
        ->name(RouteName::LEAGUE_TOP_ASSISTS)
        ->middleware([Convert::keys('id'), Mw\CheckCoversLeagueTopAssistsMiddleware::class]);
});

//FIxtures Routes
Route::prefix('fixtures')->group(function () {
    Route::get('live', Controllers\FetchLiveFixturesController::class)
        ->name(RouteName::LIVE_FIXTURES)
        ->middleware('cache.headers:max_age=60');

    Route::get('date', Controllers\FetchFixturesByDateController::class)->name(RouteName::FIXTURES_BY_DATE);

    Route::get('players/statistics', Controllers\FetchFixturePlayersStatisticsController::class)
        ->middleware([Convert::keys('id', 'team'), MW\EnsureCoversPlayerStatisticsMiddleware::class])
        ->name(RouteName::FIXTURE_PLAYERS_STAT);

    Route::get('find', Controllers\FetchFixtureController::class)
        ->name(RouteName::FIND_FIXTURE)
        ->middleware([Convert::keys('id'), MW\SetFindFixtureResponseHeadersMiddleware::class]);

    Route::get('lineup', Controllers\FetchFixtureLineUpController::class)
        ->name(RouteName::FIXTURE_LINEUP)
        ->middleware([Convert::keys('id'), MW\CheckCoversFixtureLineUpMiddleware::class]);

    Route::get('stats', Controllers\FetchFixtureStatisticsController::class)
        ->name(RouteName::FIXTURE_STATS)
        ->middleware([Convert::keys('id', 'team'), MW\CheckCoversFixtureStatisticsMiddleware::class]);

    Route::get('events', Controllers\FetchFixtureEventsController::class)
        ->name(RouteName::FIXTURE_EVENTS)
        ->middleware([Convert::keys('id'), MW\CheckCoversEventsMiddleware::class]);

    Route::post('predict', PC\PredictFixtureController::class)->middleware([
        TransactionMiddleware::class,
        Authenticate::user(),
        Convert::keys('fixture_id'),
        PCM\EnsureUserCanPredictFixtureMiddleware::class,
        PCM\EnsureFixtureCanBePredictedMiddleware::class
    ])->name(RouteName::PREDICT_FIXTURE);

    Route::get('predictions', PC\FetchFixturePredictionsController::class)
        ->name(RouteName::FIXTURE_PREDICTIONS)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=600');

    Route::post('comments', Controllers\CreateCommentController::class)
        ->name(RouteName::CREATE_COMMENT)
        ->middleware([Convert::keys('fixture_id'), TransactionMiddleware::class, Authenticate::user(), EnsureFixtureExistsMiddleware::key('fixture_id')]);

    Route::get('comments', Controllers\FetchFixtureCommentsController::class)
        ->name(RouteName::FIXTURE_COMMENTS)
        ->middleware(Convert::keys('id'), EnsureFixtureExistsMiddleware::key('id'));

    Route::delete('comments', Controllers\DeleteCommentController::class)
        ->name(RouteName::DELETE_COMMENT)
        ->middleware([TransactionMiddleware::class, Authenticate::user()]);
});

//Coaches routes
Route::prefix('coachs')->group(function () {
    Route::get('find', Controllers\FetchCoachConttroller::class)
        ->name(RouteName::FIND_COACH)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=86400');

    Route::get('team_history', Controllers\FetchCoachCareerHistoryController::class)
        ->name(RouteName::COACH_CAREER_HISTORY)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=86400');
});

//Players routes
Route::prefix('players')->group(function () {
    Route::get('find', Controllers\FetchPlayerController::class)
        ->name(RouteName::FIND_PLAYER)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=86400');

    Route::get('transfer_history', Controllers\FetchPlayerTransferHistoryController::class)
        ->name(RouteName::PLAYER_TRANSFER_HISTORY)
        ->middleware(Convert::keys('id'), 'cache.headers:max_age=86400');
});

//Favourites routes
Route::middleware([TransactionMiddleware::class, Authenticate::user()])->group(function () {

    Route::post('favourites/team', FC\AddTeamTofavouritesController::class)
        ->name(RouteName::ADD_TEAM_TO_FAVOURITES)
        ->middleware(Convert::keys('id'));

    Route::post('favourites/league', FC\AddLeagueToFavouritesController::class)
        ->name(RouteName::ADD_LEAGUE_TO_FAVOURITES)
        ->middleware(Convert::keys('id'));
});

//News
Route::get('news', \Module\Football\News\FetchNewsHeadlinesController::class)->name(RouteName::NEWS);
