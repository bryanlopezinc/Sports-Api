<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\Cache\LeaguesFixturesByDateCacheRepository;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Repositories\FetchLeagueFixturesByDateRepositoryInterface;

final class FetchLeagueFixturesByDateService
{
    public function __construct(
        private FetchLeagueFixturesByDateRepositoryInterface $repository,
        private LeaguesFixturesByDateCacheRepository $cache,
    ) {
    }

    public function fetch(LeagueId $leagueId, Date $date, Season $season): FixturesCollection
    {
        if ($this->cache->has($leagueId, $season, $date)) {
            return $this->cache->get($leagueId, $season, $date);
        }

        $fixturesCollection = $this->repository->findBy($leagueId, $season, $date);

        $this->cache->put($leagueId, $season, $date, $fixturesCollection, $this->determineTimeToLiveFrom($date, $fixturesCollection));

        return $fixturesCollection;
    }

    private function determineTimeToLiveFrom(Date $date, FixturesCollection $collection): TimeToLive
    {
        if (!$date->toCarbon()->isSameWeek()) {
            return TimeToLive::minutes(10);
        }

        if ($collection->anyFixtureIsInProgress()) {
            return TimeToLive::minutes(1);
        }

        if (!$collection->hasUpcomingFixture()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        return TimeToLive::minutes(30);
    }
}
