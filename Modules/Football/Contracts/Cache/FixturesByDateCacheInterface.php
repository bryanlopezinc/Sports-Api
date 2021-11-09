<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\LeagueFixturesGroup;

interface FixturesByDateCacheInterface
{
    public function has(Date $date): bool;

    /**
     * @param array<LeagueFixturesGroup> $leagueFixtures
     */
    public function put(Date $date, array $leagueFixtures, TimeToLive $ttl): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(Date $date): array;
}
