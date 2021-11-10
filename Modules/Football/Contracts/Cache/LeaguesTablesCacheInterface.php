<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTable;

interface LeaguesTablesCacheInterface
{
    public function has(LeagueId $leagueId, Season $season): bool;

    /**
     * @throws \Module\Football\Exceptions\Cache\CannotCacheEmptyLeagueTableException
     */
    public function cache(LeagueTable $leagueTable, Season $season, TimeToLive $ttl): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(LeagueId $leagueId, Season $season): LeagueTable;
}
