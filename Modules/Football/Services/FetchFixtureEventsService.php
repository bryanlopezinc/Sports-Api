<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use Module\Football\DTO\Fixture;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DetermineFixtureTimeToLiveInCache;
use Module\Football\Collections\FixtureEventsCollection;
use Module\Football\Contracts\Cache\FixturesEventsCacheInterface;
use Module\Football\Contracts\Repositories\FetchFixtureEventsRepositoryInterface;

final class FetchFixtureEventsService
{
    public function __construct(
        private FixturesEventsCacheInterface $cache,
        private FetchFixtureService $findFixtureService,
        private FetchFixtureEventsRepositoryInterface $repository,
        private DetermineFixtureTimeToLiveInCache $determineFixtureTtl
    ) {
    }

    public function fetch(FixtureId $fixtureId): FixtureEventsCollection
    {
        if ($this->cache->has($fixtureId)) {
            return $this->cache->get($fixtureId);
        }

        $events = $this->repository->events($fixtureId);

        $this->cache->cache($fixtureId, $events, $this->determineCacheTtlFrom($this->findFixtureService->fetchFixture($fixtureId)));

        return $events;
    }

    private function determineCacheTtlFrom(Fixture $fixture): TimeToLive
    {
        return $this->determineFixtureTtl->for($fixture);
    }
}
