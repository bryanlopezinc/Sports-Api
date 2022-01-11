<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Illuminate\Contracts\Cache\Repository;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Repositories\FetchLiveFixturesRepositoryInterface;

final class LiveFixturesCacheRepository implements FetchLiveFixturesRepositoryInterface
{
    public function __construct(
        private Repository $repository,
        private FetchLiveFixturesRepositoryInterface $liveFixturesRepository
    ) {
    }

    public function FetchLiveFixtures(): FixturesCollection
    {
        $key = (string) new CachePrefix($this);

        return $this->repository->remember($key, now()->addMinute(), fn () => $this->liveFixturesRepository->FetchLiveFixtures());
    }
}
