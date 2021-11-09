<?php

declare(strict_types=1);

namespace Module\Football\Services;

use Module\Football\DTO\League;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Collections\LeagueIdsCollection;
use App\Exceptions\Http\ResourceNotFoundHttpException;
use Module\Football\Contracts\Cache\LeaguesCacheInterface;
use Module\Football\Contracts\Repositories\FetchLeagueRepositoryInterface;

final class FetchLeagueService
{
    public function __construct(private FetchLeagueRepositoryInterface $repository, private LeaguesCacheInterface $cache)
    {
    }

    public function leagueExists(LeagueId $id): bool
    {
        try {
            return $this->findManyById($id->toCollection())->isNotEmpty();
        } catch (ResourceNotFoundHttpException) {
            return false;
        }
    }

    /**
     * @throws ResourceNotFoundHttpException
     */
    public function findByIdAndSeason(LeagueId $id, Season $season): League
    {
        try {
            return $this->cache->findByIdAndSeason($id, $season);
        } catch (ItemNotInCacheException) {
        }

        $this->cacheLeagues($league = $this->repository->findByIdAndSeason($id, $season));

        return $league;
    }

    /**
     * @throws ResourceNotFoundHttpException
     */
    public function findManyById(LeagueIdsCollection $ids): LeaguesCollection
    {
        $ids = $ids->unique();

        $cacheResult = $this->cache->findManyById($ids);

        if ($ids->count() === $cacheResult->count()) {
            return $cacheResult;
        }

        $leaguesCollection = $this->repository->findManyById($ids->except($cacheResult->pluckIds()));

        $this->cacheLeagues($leaguesCollection);

        return $leaguesCollection->merge($cacheResult->toArray());
    }

    private function cacheLeagues(LeaguesCollection|League $collection): void
    {
        $cacheLeagueService = new CacheLeagueService($this->cache);

        if ($collection instanceof League) {
            $cacheLeagueService->cacheLeague($collection);

            return;
        }

        $cacheLeagueService->cacheMany($collection);
    }
}
