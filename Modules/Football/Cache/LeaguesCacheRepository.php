<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\DTO\League;
use Module\Football\ValueObjects\Season;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\DTO\Builders\LeagueBuilder;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Collections\LeagueIdsCollection;
use Module\Football\Contracts\Cache\LeaguesCacheInterface;

final class LeaguesCacheRepository implements LeaguesCacheInterface
{
    public function __construct(private Repository $repository, private LeaguesSeasonsCacheRepository $leaguesSeasonsCache)
    {
    }

    public function cache(League $league, TimeToLive $ttl): bool
    {
        $this->leaguesSeasonsCache->cache($league->getId(), $league->getSeason(), $ttl);

        return $this->repository->put($this->prepareKey($league->getId()), $league, $ttl->ttl());
    }

    public function has(LeagueId $leagueId): bool
    {
        return $this->repository->has($this->prepareKey($leagueId));
    }

    private function prepareKey(LeagueId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function findById(LeagueId $leagueId): League
    {
        return $this->findManyById($leagueId->toCollection())
            ->toLaravelCollection()
            ->whenEmpty(fn () => throw new ItemNotInCacheException())
            ->first();
    }

    public function findByIdAndSeason(LeagueId $leagueId, Season $season): League
    {
        return LeagueBuilder::fromLeague($this->findById($leagueId))
            ->setSeason($this->leaguesSeasonsCache->get($leagueId, $season))
            ->build();
    }

    public function findManyById(LeagueIdsCollection $ids): LeaguesCollection
    {
        $keys = $ids->toLaravelCollection()->map(fn (LeagueId $id): string => $this->prepareKey($id))->all();

        $result = collect($this->repository->getMultiple($keys))->reject(fn ($value): bool => is_null($value))->all();

        return new LeaguesCollection($result);
    }
}
