<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\Config;
use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\DTO\League;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTable;
use Module\Football\Contracts\Cache\LeaguesTablesCacheInterface;
use Module\Football\Contracts\Repositories\FetchLeagueStandingRepositoryInterface;

final class FetchLeagueStandingService
{
    public function __construct(
        private LeaguesTablesCacheInterface $cache,
        private FetchLeagueFixturesByDateService $fetchFixturesByDate,
        private FetchLeagueStandingRepositoryInterface $repository,
    ) {
    }

    public function fetch(LeagueId $id, Season $season): LeagueTable
    {
        if ($this->cache->has($id, $season)) {
            return $this->cache->get($id, $season);
        }

        $leagueTable = $this->repository->getLeagueTable($id, $season);

        $this->cache->cache($leagueTable, $season, $this->determineTimeToLiveFrom($leagueTable->getLeague()));

        return $leagueTable;
    }

    private function determineTimeToLiveFrom(League $league): TimeToLive
    {
        //Cache league table for shorter period if it's not for current season
        if (!$league->getSeason()->isCurrentSeason()) {
            return TimeToLive::minutes(Config::get('football.cache.leaguesStandings.ttlWhenNotCurrentSeason'));
        }

        $leaguesFixturesForToday = $this->fetchFixturesByDate->fetch(
            $league->getId(),
            new Date(today()->toDateString()),
            $league->getSeason()->getSeason()
        );

        if ($leaguesFixturesForToday->isEmpty() || $leaguesFixturesForToday->allFixturesArefinished()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        //if league does not have any fixture that is confirmed to start
        if (!$leaguesFixturesForToday->hasUpcomingFixture()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        if ($leaguesFixturesForToday->anyFixtureIsInProgress()) {
            return TimeToLive::minutes(Config::get('football.cache.leaguesStandings.ttlWhenHasFixtureInProgress'));
        }

        //Cache league until next upcomming fixture
        return TimeToLive::seconds(
            now()->diffInSeconds($leaguesFixturesForToday->nextUpcomingFixture()->date()->toCarbon()->toDateTimeString())
        );
    }
}
