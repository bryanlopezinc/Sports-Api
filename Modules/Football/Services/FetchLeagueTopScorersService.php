<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\Cache\LeaguesTopScorersCacheRepository;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTopScorersCollection;
use Module\Football\Contracts\Repositories\FetchLeagueTopScorersRepositoryInterface;

final class FetchLeagueTopScorersService
{
    public function __construct(
        private LeaguesTopScorersCacheRepository $cache,
        private FetchLeagueTopScorersRepositoryInterface $repository,
        private FetchLeagueFixturesByDateService $leagueFixturesService,
    ) {
    }

    public function fetch(LeagueId $leagueId, Season $season): LeagueTopScorersCollection
    {
        if ($this->cache->has($leagueId, $season)) {
            return $this->cache->get($leagueId, $season);
        }

        $topScorers = $this->repository->topScorerers($leagueId, $season);

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
