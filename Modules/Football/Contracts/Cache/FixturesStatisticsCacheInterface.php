<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureStatistic\FixtureStatistics;

interface FixturesStatisticsCacheInterface
{
    public function cache(FixtureStatistics $fixtureStatistics, TimeToLive $ttl): bool;

    public function exists(FixtureId $fixtureId): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(FixtureId $fixtureId): FixtureStatistics;
}
