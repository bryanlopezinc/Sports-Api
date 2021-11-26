<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use Module\Football\DTO\Fixture;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DetermineFixtureTimeToLiveInCache;
use Module\Football\Collections\FixturePlayersStatisticsCollection;
use Module\Football\Contracts\Cache\FixturesPlayersStatisticsCacheInterface;
use Module\Football\Contracts\Repositories\FetchPlayersStatisticsRepositoryInterface;

final class FetchFixturePlayersStatisticsService
{
    public function __construct(
        private FetchPlayersStatisticsRepositoryInterface $repository,
        private FixturesPlayersStatisticsCacheInterface $cache,
        private FetchFixtureService $findFixtureService,
        private DetermineFixtureTimeToLiveInCache $ttlDeterminer
    ) {
    }

    public function fetch(FixtureId $fixtureId): FixturePlayersStatisticsCollection
    {
        if ($this->cache->exists($fixtureId)) {
            return $this->cache->get($fixtureId);
        }

        $statistics = $this->repository->fetchStatistics($fixtureId);

        $this->cache->cache($fixtureId, $statistics, $this->determineCacheTtlFrom($this->findFixtureService->fetchFixture($fixtureId)));

        return $statistics;
    }

    private function determineCacheTtlFrom(Fixture $fixture): TimeToLive
    {
        return $this->ttlDeterminer->for($fixture);
    }
}
