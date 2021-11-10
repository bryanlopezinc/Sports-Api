<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\Season;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\LeagueTable;
use Module\Football\Contracts\Cache\LeaguesTablesCacheInterface;
use Module\Football\Exceptions\Cache\CannotCacheEmptyLeagueTableException;

final class LeagueStandingCacheRepository implements LeaguesTablesCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function cache(LeagueTable $leagueTable, Season $season, TimeToLive $ttl): bool
    {
        if ($leagueTable->isEmpty()) {
            throw new CannotCacheEmptyLeagueTableException();
        }

        return $this->repository->put(
            $this->prepareKey($leagueTable->getLeague()->getId(), $season),
            $leagueTable,
            $ttl->ttl()
        );
    }

    public function has(LeagueId $leagueId, Season $season): bool
    {
        return $this->repository->has($this->prepareKey($leagueId, $season));
    }

    private function prepareKey(LeagueId $leagueId, Season $season): string
    {
        return new CachePrefix($this) . $leagueId->toInt() . ':' . $season->toInt();
    }

    public function get(LeagueId $leagueId, Season $season): LeagueTable
    {
        return $this->repository->get($this->prepareKey($leagueId, $season), fn () => throw new ItemNotInCacheException());
    }
}
