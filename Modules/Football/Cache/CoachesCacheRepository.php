<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Module\Football\DTO\Coach;
use Module\Football\ValueObjects\CoachId;
use Illuminate\Contracts\Cache\Repository;
use App\Utils\Config;
use Module\Football\Contracts\Repositories\FetchCoachRepositoryInterface;

final class CoachesCacheRepository implements FetchCoachRepositoryInterface
{
    public function __construct(
        private Repository $repository,
        private FetchCoachRepositoryInterface $fetchCoachRepository
    ) {
    }

    public function byId(CoachId $id): Coach
    {
        $key = new CachePrefix($this) . $id->toInt();

        $ttl = now()->addDays(Config::get('football.cache.coaches.defaultTtl'));

        return $this->repository->remember($key, $ttl, fn () => $this->fetchCoachRepository->byId($id));
    }
}
