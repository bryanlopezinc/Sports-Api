<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

final class FixturesPlayersStatisticsCacheRepository
{
    public function __construct(private Repository $repository)
    {
    }

    public function cache(FixtureId $fixtureId, FixturePlayersStatisticsCollection $statistics, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($fixtureId), $statistics, $ttl->ttl());
    }

    private function prepareKey(FixtureId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function exists(FixtureId $fixtureId): bool
    {
        return $this->repository->has($this->prepareKey($fixtureId));
    }

    public function get(FixtureId $fixtureId): FixturePlayersStatisticsCollection
    {
        return $this->repository->get($this->prepareKey($fixtureId), fn () => throw new ItemNotInCacheException());
    }
}
