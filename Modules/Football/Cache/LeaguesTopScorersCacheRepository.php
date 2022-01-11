<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\Season;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\LeagueTopScorersCollection;
use Module\Football\Contracts\Cache\LeaguesTopScorersCacheInterface;

final class LeaguesTopScorersCacheRepository implements LeaguesTopScorersCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function has(LeagueId $leagueId, Season $season): bool
    {
        return $this->repository->has($this->prepareKey($leagueId, $season));
    }

    public function cache(LeagueId $leagueId, Season $season, LeagueTopScorersCollection $collection, TimeToLive $ttl): bool
    {
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

    public function get(LeagueId $leagueId, Season $season): LeagueTopScorersCollection
    {
        return $this->repository->get($this->prepareKey($leagueId, $season), fn () => throw new ItemNotInCacheException());
    }
}
