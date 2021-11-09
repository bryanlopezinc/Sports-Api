<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\Config;
use App\Utils\TimeToLive;
use Module\Football\DTO\League;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Contracts\Cache\LeaguesCacheInterface;

final class CacheLeagueService
{
    public function __construct(private LeaguesCacheInterface $cache)
    {
    }

    public function cacheLeague(League $league): void
    {
        $this->cacheMany(new LeaguesCollection([$league]));
    }

    public function cacheMany(LeaguesCollection $collection): void
    {
        $collection->toLaravelCollection()->each(function (League $league): void {
            $this->cache->cache($league, TimeToLive::days(Config::get('football.cache.leagues.ttl')));
        });
    }
}
