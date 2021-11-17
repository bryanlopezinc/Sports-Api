<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\CoachId;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\CoachCareerHistory;
use Module\Football\Contracts\Cache\CoachesCareesHistoryCacheInterface;

final class CoachesCareersCacheRepository implements CoachesCareesHistoryCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function has(CoachId $coachId): bool
    {
        return $this->repository->has($this->prepareKey($coachId));
    }

    public function get(CoachId $coachId): CoachCareerHistory
    {
        return $this->repository->get($this->prepareKey($coachId), fn () => throw new ItemNotInCacheException());
    }

    private function prepareKey(CoachId $coachId): string
    {
        return new CachePrefix($this) . $coachId->toInt();
    }

    public function put(CoachId $id, CoachCareerHistory $history, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($id), $history, $ttl->ttl());
    }
}
