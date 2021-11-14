<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\Season;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\LeagueTopAssistsCollection;
use Module\Football\Contracts\Cache\LeaguesTopAssistsCacheInterface;
use Module\Football\Exceptions\Cache\CannotCacheEmptyTopAssistsException;

final class LeaguesTopAsisstsCacheRepository implements LeaguesTopAssistsCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function has(LeagueId $leagueId, Season $season): bool
    {
        return $this->repository->has($this->prepareKey($leagueId, $season));
    }

    public function cache(LeagueId $leagueId, Season $season, LeagueTopAssistsCollection $collection, TimeToLive $ttl): bool
    {
        if ($collection->isEmpty()) {
            throw new CannotCacheEmptyTopAssistsException();
        }

        return $this->repository->put(
            $this->prepareKey($leagueId, $season),
            $collection,
            $ttl->ttl()
        );
    }

    private function prepareKey(LeagueId $leagueId, Season $season): string
    {
        return new CachePrefix($this) . $leagueId->toInt() . ':' . $season->toInt();
    }

    public function get(LeagueId $leagueId, Season $season): LeagueTopAssistsCollection
    {
        return $this->repository->get($this->prepareKey($leagueId, $season), fn () => throw new ItemNotInCacheException());
    }
}