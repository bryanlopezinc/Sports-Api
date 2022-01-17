<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use Module\Football\DTO\Fixture;
use Module\Football\FixtureLineUp;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DetermineFixtureTimeToLiveInCache;
use Module\Football\Contracts\Cache\FixturesLineUpCacheInterface;
use Module\Football\Contracts\Repositories\FetchFixtureLineUpRepositoryInterface;

final class FetchFixtureLineUpService
{
    public function __construct(
        private FetchFixtureLineUpRepositoryInterface $repository,
        private FixturesLineUpCacheInterface $cache,
        private FetchFixtureService $service,
        private DetermineFixtureTimeToLiveInCache $determineFixtureTtl
    ) {
    }

    public function fetchLineUp(FixtureId $id): FixtureLineUp
    {
        if ($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        $fixtureLineUp = $this->repository->fetchLineUp($id);

        $this->cache->put($id, $fixtureLineUp, $this->determineCacheTtlFrom($this->service->fetchFixture($id)));

        return $fixtureLineUp;
    }

    private function determineCacheTtlFrom(Fixture $fixture): TimeToLive
    {
        //Cache fixture lineup until fixture start time
        if ($fixture->status()->isNotStarted()) {
            return TimeToLive::seconds(now()->diffInSeconds($fixture->date()->toCarbon()->toDateTimeString()));
        }

        return $this->determineFixtureTtl->for($fixture);
    }
}
