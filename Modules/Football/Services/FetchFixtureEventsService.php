<?php

declare(strict_types=1);

namespace Module\Football\Services;

use Module\Football\Cache\FixtureEventsCacheRepository;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DetermineFixtureTimeToLiveInCache;
use Module\Football\Collections\FixtureEventsCollection;
use Module\Football\Contracts\Repositories\FetchFixtureEventsRepositoryInterface;

final class FetchFixtureEventsService
{
    public function __construct(
        private FixtureEventsCacheRepository $cache,
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

        $this->cache->cache($fixtureId, $events, $this->determineFixtureTtl->for($this->findFixtureService->fetchFixture($fixtureId)));

        return $events;
    }
}
