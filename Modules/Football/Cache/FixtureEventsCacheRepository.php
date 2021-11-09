<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixtureEventsCollection;
use Module\Football\Contracts\Cache\FixturesEventsCacheInterface;

final class FixtureEventsCacheRepository implements FixturesEventsCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function cache(FixtureId $fixtureId, FixtureEventsCollection $fixture, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($fixtureId), $fixture, $ttl->ttl());
    }

    private function prepareKey(FixtureId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function has(FixtureId $fixtureId): bool
    {
        return $this->repository->has($this->prepareKey($fixtureId));
    }

    public function get(FixtureId $fixtureId): FixtureEventsCollection
    {
        return $this->repository->get($this->prepareKey($fixtureId), fn () => throw new ItemNotInCacheException());
    }
}
