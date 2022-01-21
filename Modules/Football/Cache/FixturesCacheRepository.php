<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\DTO\Fixture;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Illuminate\Support\Collection;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Collections\FixturesCollection;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Contracts\Cache\FixturesCacheInterface;

final class FixturesCacheRepository implements FixturesCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function cache(Fixture $fixture, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($fixture->id()), $fixture, $ttl->ttl());
    }

    private function prepareKey(FixtureId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function has(FixtureId $fixtureId): bool
    {
        return $this->repository->has($this->prepareKey($fixtureId));
    }

    public function get(FixtureId $fixtureId): Fixture
    {
        return $this->repository->get($this->prepareKey($fixtureId), fn () => throw new ItemNotInCacheException());
    }

    public function getMany(FixtureIdsCollection $fixtureIds): FixturesCollection
    {
        return $fixtureIds->toLaravelCollection()
            ->map(fn (FixtureId $id) => $this->prepareKey($id))
            ->pipe(fn (Collection $keys) => new Collection($this->repository->getMultiple($keys->all(), false)))
            ->filter(fn ($value) => $value !== false)
            ->pipe(fn (Collection $collection) => new FixturesCollection($collection->all()));
    }
}
