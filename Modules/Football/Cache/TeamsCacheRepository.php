<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TeamId;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Contracts\Cache\TeamsCacheInterface;

final class TeamsCacheRepository implements TeamsCacheInterface
{
    public function __construct(private Repository $repository)
    {
    }

    public function has(TeamId $teamId): bool
    {
        return $this->repository->has($this->prepareKey($teamId));
    }

    private function prepareKey(TeamId $id): string
    {
        return new CachePrefix($this) . $id->toInt();
    }

    public function cache(Team $team, TimeToLive $ttl): bool
    {
        return $this->repository->put($this->prepareKey($team->getId()), $team, $ttl->ttl());
    }

    public function getMany(TeamIdsCollection $ids): TeamsCollection
    {
        $keys = $ids->toLaravelCollection()->map(fn (TeamId $teamId) => $this->prepareKey($teamId))->all();

        $result = collect($this->repository->getMultiple($keys))->reject(fn ($value) => is_null($value))->all();

        return new TeamsCollection($result);
    }
}
