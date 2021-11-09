<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use App\ValueObjects\Date;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\Contracts\Cache\FixturesByDateCacheInterface;

final class FixturesByDateCacheRepository implements FixturesByDateCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function has(Date $date): bool
    {
        return $this->repository->has($this->prepareKey($date));
    }

    /**
     * {@inheritdoc}
     */
    public function put(Date $date, array $fixtures, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($date), $fixtures, $ttl->ttl());
    }

    public function get(Date $date): array
    {
        return $this->repository->get($this->prepareKey($date), fn () => throw new \App\Exceptions\ItemNotInCacheException);
    }

    private function prepareKey(Date $date): string
    {
        return new CachePrefix($this) . $date->toCarbon()->toDateString();
    }
}
