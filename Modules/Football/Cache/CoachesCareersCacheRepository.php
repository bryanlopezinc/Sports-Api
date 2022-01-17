<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Module\Football\ValueObjects\CoachId;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\Collections\CoachCareerHistory;
use Module\Football\Contracts\Repositories\FetchCoachCareerHistoryRepositoryInterface;

final class CoachesCareersCacheRepository implements FetchCoachCareerHistoryRepositoryInterface
{
    public function __construct(
        private Repository $repository,
        private FetchCoachCareerHistoryRepositoryInterface $fetchCoachCareerHistoryRepository
    ) {
    }

    public function byId(CoachId $id): CoachCareerHistory
    {
        $key = new CachePrefix($this) . $id->toInt();

        return $this->repository->remember($key, now()->addDays(5), fn () => $this->fetchCoachCareerHistoryRepository->byId($id));
    }
}
