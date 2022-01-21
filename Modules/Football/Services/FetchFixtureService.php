<?php

declare(strict_types=1);

namespace Module\Football\Services;

use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Collections\FixturesCollection;
use Module\Football\DTO\Fixture;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DetermineFixtureTimeToLiveInCache;
use Module\Football\Contracts\Cache\FixturesCacheInterface;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;

final class FetchFixtureService
{
    public function __construct(
        private FetchFixtureRepositoryInterface $client,
        private FixturesCacheInterface $cache,
    ) {
    }

    /**
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function fetchFixture(FixtureId $fixtureId): Fixture
    {
        if ($this->cache->has($fixtureId)) {
            return $this->cache->get($fixtureId);
        }

        $this->cache($fixture = $this->client->FindFixtureById($fixtureId));

        return $fixture;
    }

    public function findMany(FixtureIdsCollection $fixtureIds): FixturesCollection
    {
        if ($fixtureIds->isEmpty()) {
            return new FixturesCollection([]);
        }

        $fixtureIds = $fixtureIds->unique();

        $cacheResult = $this->cache->getMany($fixtureIds);

        if ($fixtureIds->count() === $cacheResult->count()) {
            return $cacheResult;
        }

        return $this->client->findManyById($fixtureIds->except($cacheResult->ids()))
            ->each(fn (Fixture $fixture) => $this->cache($fixture))
            ->merge($cacheResult->toArray());
    }

    private function cache(Fixture $fixture): bool
    {
        return $this->cache->cache($fixture, (new DetermineFixtureTimeToLiveInCache)->for($fixture));
    }
}
