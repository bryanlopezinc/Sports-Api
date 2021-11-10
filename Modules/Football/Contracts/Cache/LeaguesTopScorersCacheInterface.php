<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTopScorersCollection;

interface LeaguesTopScorersCacheInterface
{
    public function has(LeagueId $leagueId, Season $season): bool;

    /**
     * @throws \Module\Football\Exceptions\Cache\CannotCacheEmptyTopScorersException
     */
    public function cache(LeagueId $leagueId, Season $season, LeagueTopScorersCollection $collection, TimeToLive $ttl): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(LeagueId $leagueId, Season $season,): LeagueTopScorersCollection;
}
