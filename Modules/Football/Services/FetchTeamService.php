<?php

declare(strict_types=1);

namespace Module\Football\Services;

use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Collections\TeamIdsCollection;
use App\Exceptions\Http\ResourceNotFoundHttpException;
use Module\Football\Contracts\Cache\TeamsCacheInterface;
use Module\Football\Contracts\Repositories\FetchTeamRepositoryInterface;

final class FetchTeamService
{
    public function __construct(
        private FetchTeamRepositoryInterface $repository,
        private TeamsCacheInterface $cache,
    ) {
    }

    /**
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findById(TeamId $id): Team
    {
        return $this->findManyById($id->toCollection())->sole();
    }

    /**
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findManyById(TeamIdsCollection $ids): TeamsCollection
    {
        $teamIds = $ids->unique();

        $cacheResult = $this->cache->getMany($teamIds);

        if ($teamIds->count() === $cacheResult->count()) {
            return $cacheResult;
        }

        $this->cacheTeams(
            $teamsCollection = $this->repository->findManyById($teamIds->except($cacheResult->pluckIds()))
        );

        return $teamsCollection->merge($cacheResult->toArray());
    }

    private function cacheTeams(TeamsCollection $teams): void
    {
        (new CacheTeamService($this->cache))->cacheMany($teams);
    }

    public function exists(TeamId $teamId): bool
    {
        try {
            return $this->findManyById($teamId->toCollection())->isNotEmpty();
        } catch (ResourceNotFoundHttpException) {
            return false;
        }
    }
}
