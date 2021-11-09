<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\DTO\LeagueSeason;
use Module\Football\ValueObjects\Season;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\LeagueSeasonsCollection;

final class LeaguesSeasonsCacheRepository
{
    public function __construct(private Repository $repository)
    {
    }

    public function get(LeagueId $id, Season $season): LeagueSeason
    {
        return $this->getLeagueSeasons($id)
            ->toLaravelCollection()
            ->filter(fn (LeagueSeason $leagueSeason) => $season->equals($leagueSeason->getSeason()))
            ->whenEmpty(fn () => throw new ItemNotInCacheException())
            ->sole();
    }

    public function cache(LeagueId $id, LeagueSeason $season, TimeToLive $ttl): bool
    {
        $collection = $this->getLeagueSeasons($id);

        if ($collection->has($season)) {
            return true;
        }

        return $this->repository->put($this->prepareKey($id), $collection->push($season), $ttl->ttl());
    }

    private function getLeagueSeasons(LeagueId $id): LeagueSeasonsCollection
    {
        return $this->repository->get($this->prepareKey($id), new LeagueSeasonsCollection([]));
    }

    private function prepareKey(LeagueId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }
}
