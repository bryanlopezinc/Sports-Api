<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixtureEventsCollection;

interface FixturesEventsCacheInterface
{
    public function cache(FixtureId $fixtureId, FixtureEventsCollection $events, TimeToLive $ttl): bool;

    public function has(FixtureId $fixtureId): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(FixtureId $fixtureId): FixtureEventsCollection;
}
