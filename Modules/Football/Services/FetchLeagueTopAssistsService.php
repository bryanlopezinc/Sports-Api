<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTopAssistsCollection;
use Module\Football\Contracts\Cache\LeaguesTopAssistsCacheInterface as Cache;
use Module\Football\Contracts\Repositories\FetchLeagueTopAssistsRepositoryInterface as Repository;

final class FetchLeagueTopAssistsService
{
    public function __construct(
        private Cache $cache,
        private Repository $repository,
        private FetchLeagueFixturesByDateService $leagueFixturesService,
    ) {
    }

    public function fetch(LeagueId $leagueId, Season $season): LeagueTopAssistsCollection
    {
        if ($this->cache->has($leagueId, $season)) {
            return $this->cache->get($leagueId, $season);
        }

        $topScorers = $this->repository->topAssists($leagueId, $season);

        $this->cache->cache($leagueId, $season, $topScorers, $this->determineCacheTtlFrom($leagueId, $season));

        return $topScorers;
    }

    private function determineCacheTtlFrom(LeagueId $leagueId, Season $season): TimeToLive
    {
        $leaguesFixturesForToday = $this->leagueFixturesService->fetch(
            $leagueId,
            new Date(today()->toDateString()),
            $season
        );

        if ($leaguesFixturesForToday->isEmpty()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        if (!$leaguesFixturesForToday->hasUpcomingFixture()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        return TimeToLive::minutes(180);
    }
}
