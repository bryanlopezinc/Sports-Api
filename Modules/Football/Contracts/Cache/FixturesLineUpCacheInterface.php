<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\FixtureLineUp;
use Module\Football\ValueObjects\FixtureId;

interface FixturesLineUpCacheInterface
{
    public function has(FixtureId $fixtureId): bool;

    public function put(FixtureId $id, FixtureLineUp $fixtureLineUp, TimeToLive $ttl): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(FixtureId $fixtureId): FixtureLineUp;
}
