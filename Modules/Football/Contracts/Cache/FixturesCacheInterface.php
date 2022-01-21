<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Collections\FixturesCollection;
use Module\Football\DTO\Fixture;
use Module\Football\ValueObjects\FixtureId;

interface FixturesCacheInterface
{
    public function has(FixtureId $fixtureId): bool;

    public function cache(Fixture $fixture, TimeToLive $ttl): bool;

    public function getMany(FixtureIdsCollection $fixtureIds): FixturesCollection;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(FixtureId $fixtureId): Fixture;
}
