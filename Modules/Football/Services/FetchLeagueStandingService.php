<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Illuminate\Support\Collection;
use Module\Football\Cache\LeagueStandingCacheRepository;
use Module\Football\DTO\League;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTable;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Contracts\Repositories\FetchLeagueStandingRepositoryInterface;
use Module\Football\Http\Requests\FetchLeagueStandingRequest;
use Module\Football\ValueObjects\TeamId;

final class FetchLeagueStandingService
{
    public function __construct(
        private LeagueStandingCacheRepository $cache,
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

    public function fromRequest(FetchLeagueStandingRequest $request): LeagueTable
    {
        $leagueTable = $this->fetch(new LeagueId($request->input('league_id')), Season::fromString($request->input('season')));

        if ($request->filled('teams')) {
            $leagueTable = $this->getCustomTeams($leagueTable, $request);
        }

        return $leagueTable;
    }

    private function getCustomTeams(LeagueTable $leagueTable, FetchLeagueStandingRequest $request): LeagueTable
    {
        $teamids = $leagueTable->teams()->pluckIds();

        $requestedTeams = collect($request->input('teams'))
            ->map(fn (string $id) => new TeamId((int) $id))
            ->each(fn (TeamId $id) => abort_if(!$teamids->has($id), 400, sprintf('Team with id %s could not be found in league table', $id->asHashedId())))
            ->pipe(fn (Collection $collection) => new TeamIdsCollection($collection->all()));

        return $leagueTable->onlyTeams($requestedTeams);
    }

    private function determineTimeToLiveFrom(League $league): TimeToLive
    {
        //Cache league table for shorter period if it's not for current season
        if (!$league->getSeason()->isCurrentSeason()) {
            return TimeToLive::minutes(30);
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
            return TimeToLive::minutes(10);
        }

        //Cache league until next upcomming fixture
        return TimeToLive::seconds(
            now()->diffInSeconds($leaguesFixturesForToday->nextUpcomingFixture()->date()->toCarbon()->toDateTimeString())
        );
    }
}
