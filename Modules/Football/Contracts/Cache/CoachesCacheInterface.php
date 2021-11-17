<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Cache;

use App\Utils\TimeToLive;
use Module\Football\DTO\Coach;
use Module\Football\ValueObjects\CoachId;

interface CoachesCacheInterface
{
    public function has(CoachId $coachId): bool;

    public function put(Coach $coach, TimeToLive $ttl): bool;

    /**
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(CoachId $coachId): Coach;
}
