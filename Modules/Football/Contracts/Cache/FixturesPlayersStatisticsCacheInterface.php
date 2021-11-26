<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

interface FixturesPlayersStatisticsCacheInterface
{
    public function cache(FixtureId $fixtureId, FixturePlayersStatisticsCollection $statistics, TimeToLive $ttl): bool;

    public function exists(FixtureId $fixtureId): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(FixtureId $fixtureId): FixturePlayersStatisticsCollection;
}
