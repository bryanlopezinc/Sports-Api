<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\FixtureLineUp;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\ValueObjects\FixtureId;

final class FixturesLineUpCacheRepository
{
    public function __construct(private Repository $repository)
    {
    }

    public function put(FixtureId $id, FixtureLineUp $lineUp, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($id), $lineUp, $ttl->ttl());
    }

    private function prepareKey(FixtureId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function has(FixtureId $fixtureId): bool
    {
        return $this->repository->has($this->prepareKey($fixtureId));
    }

    public function get(FixtureId $fixtureId): FixtureLineUp
    {
        return $this->repository->get($this->prepareKey($fixtureId), fn () => throw new ItemNotInCacheException());
    }
}
