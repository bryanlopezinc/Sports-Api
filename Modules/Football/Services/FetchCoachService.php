<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\Config;
use App\Utils\TimeToLive;
use Module\Football\Contracts\Cache\CoachesCacheInterface as Cache;
use Module\Football\Contracts\Repositories\FetchCoachRepositoryInterface as Repository;
use Module\Football\DTO\Coach;
use Module\Football\ValueObjects\CoachId;

final class FetchCoachService
{
    public function __construct(private Repository $repository, private Cache $cache)
    {
    }

    /**
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findById(CoachId $id): Coach
    {
        if ($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        $coach = $this->repository->byId($id);

        $this->cache->put($coach, TimeToLive::days(Config::get('football.cache.coaches.defaultTtl')));

        return $coach;
    }
}
