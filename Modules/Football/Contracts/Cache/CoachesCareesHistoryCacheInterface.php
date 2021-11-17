<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Collections\CoachCareerHistory;

interface CoachesCareesHistoryCacheInterface
{
    public function has(CoachId $coachId): bool;

    public function put(CoachId $id, CoachCareerHistory $history, TimeToLive $ttl): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(CoachId $coachId): CoachCareerHistory;
}
