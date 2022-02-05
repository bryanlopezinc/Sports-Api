<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use Module\Football\Cache\FixturesStatisticsCacheRepository;
use Module\Football\DTO\Fixture;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DetermineFixtureTimeToLiveInCache;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Contracts\Repositories\FetchFixtureStatisticsRepositoryInterface;

final class FetchFixtureStatisticsService
{
    public function __construct(
        private FetchFixtureStatisticsRepositoryInterface $repository,
        private FixturesStatisticsCacheRepository $cache,
        private FetchFixtureService $findFixtureService,
        private DetermineFixtureTimeToLiveInCache $ttlDeterminer
    ) {
    }

    public function fetch(FixtureId $fixtureId): FixtureStatistics
    {
        if ($this->cache->exists($fixtureId)) {
            return $this->cache->get($fixtureId);
        }

        $statistics = $this->repository->fetchStats($fixtureId);

        $this->cache->cache($statistics, $this->determineCacheTtlFrom($this->findFixtureService->fetchFixture($fixtureId)));

        return $statistics;
    }

    private function determineCacheTtlFrom(Fixture $fixture): TimeToLive
    {
        return $this->ttlDeterminer->for($fixture);
    }
}
