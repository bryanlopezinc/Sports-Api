<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\DTO\League;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Collections\LeagueIdsCollection;

interface LeaguesCacheInterface
{
    public function has(LeagueId $leagueId): bool;

    public function cache(League $league, TimeToLive $ttl): bool;

    public function findManyById(LeagueIdsCollection $ids): LeaguesCollection;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function findById(LeagueId $leagueId): League;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function findByIdAndSeason(LeagueId $leagueId, Season $season): League;
}
