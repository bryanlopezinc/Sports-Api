<?php

declare(strict_types=1);

namespace Module\Football\Services;

use Module\Football\ValueObjects\CoachId;
use Module\Football\Collections\CoachCareerHistory;
use Module\Football\Contracts\Cache\CoachesCareesHistoryCacheInterface as Cache;
use Module\Football\Contracts\Repositories\FetchCoachCareerHistoryRepositoryInterface as Repository;

final class FetchCoachCareerHistoryService
{
    public function __construct(
        private Cache $cache,
        private Repository $repository,
        private CacheCoachCareerHistoryService $cacheCoachCareerHistoryService,
    ) {
    }

    /**
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findById(CoachId $id): CoachCareerHistory
    {
        if ($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        $this->cacheCoachCareerHistoryService->cache($id, $careerHistory = $this->repository->byId($id));

        return $careerHistory;
    }
}
