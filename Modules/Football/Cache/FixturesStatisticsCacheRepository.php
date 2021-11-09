<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Contracts\Cache\FixturesStatisticsCacheInterface;

final class FixturesStatisticsCacheRepository implements FixturesStatisticsCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function cache(FixtureStatistics $stats, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($stats->fixtureId()), $stats, $ttl->ttl());
    }

    private function prepareKey(FixtureId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function exists(FixtureId $fixtureId): bool
    {
        return $this->repository->has($this->prepareKey($fixtureId));
    }

    public function get(FixtureId $fixtureId): FixtureStatistics
    {
        return $this->repository->get($this->prepareKey($fixtureId), fn () => throw new ItemNotInCacheException());
    }
}
