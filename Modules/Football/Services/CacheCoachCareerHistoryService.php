<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\Config;
use App\Utils\TimeToLive;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Collections\CoachCareerHistory;
use Module\Football\Contracts\Cache\CoachesCareesHistoryCacheInterface as Cache;

final class CacheCoachCareerHistoryService
{
    public function __construct(private Cache $cache)
    {
    }

    public function cache(CoachId $id, CoachCareerHistory $history): bool
    {
        return $this->cache->put($id, $history, TimeToLive::days(Config::get('football.cache.coachesCareers.defaultTtl')));
    }
}
