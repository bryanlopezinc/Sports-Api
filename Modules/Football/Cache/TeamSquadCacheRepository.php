<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\TeamId;
use Illuminate\Contracts\Cache\Repository;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Contracts\Cache\TeamsSquadsCacheInterface;
use Module\Football\Exceptions\Cache\CannotCacheEmptyTeamSquadException;

final class TeamSquadCacheRepository implements TeamsSquadsCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function has(TeamId $teamId): bool
    {
        return $this->repository->has($this->prepareKey($teamId));
    }

    public function cache(TeamId $teamId, PlayersCollection $playersCollection, TimeToLive $ttl): void
    {
        throw_if($playersCollection->isEmpty(), new CannotCacheEmptyTeamSquadException());

        $this->repository->put($this->prepareKey($teamId), $playersCollection, $ttl->ttl());
    }

    private function prepareKey(TeamId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function getTeamSquad(TeamId $teamId): PlayersCollection
    {
        return $this->repository->get($this->prepareKey($teamId), fn () => throw new ItemNotInCacheException());
    }
}
