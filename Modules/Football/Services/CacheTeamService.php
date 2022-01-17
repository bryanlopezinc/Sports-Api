<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use Module\Football\DTO\Team;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Contracts\Cache\TeamsCacheInterface;

final class CacheTeamService
{
    public function __construct(private TeamsCacheInterface $cache)
    {
    }

    public function cache(Team $teams): void
    {
        $this->cacheMany(new TeamsCollection([$teams]));
    }

    public function cacheMany(TeamsCollection $teams): void
    {
        $teams->toLaravelCollection()->each(function (Team $team) {
            $this->cache->cache($team, TimeToLive::days(1));
        });
    }
}
