<?php

declare(strict_types=1);

namespace Module\Football\Services;

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

    public function fetchFixture(FixtureId $fixtureId): Fixture
    {
        if ($this->cache->has($fixtureId)) {
            return $this->cache->get($fixtureId);
        }

        $this->cache($fixture = $this->client->FindFixtureById($fixtureId));

        return $fixture;
    }

    private function cache(Fixture $fixture): bool
    {
        return $this->cache->cache($fixture, (new DetermineFixtureTimeToLiveInCache)->for($fixture));
    }
}
