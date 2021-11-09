<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\FixturesCollection;

interface LeaguesFixturesByDateCacheInterface
{
    public function put(
        LeagueId $leagueId,
        Season $season,
        Date $date,
        FixturesCollection $fixturesCollection,
        TimeToLive $ttl
    ): bool;

    public function has(LeagueId $leagueId, Season $season, Date $date): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(LeagueId $leagueId, Season $season, Date $date): FixturesCollection;
}
