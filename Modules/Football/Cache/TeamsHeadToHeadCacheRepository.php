<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\TeamId;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\TeamsHeadToHead;

final class TeamsHeadToHeadCacheRepository
{
    public function __construct(private Repository $repository)
    {
    }

    /**
     * The order the team ids args are placed does not matter.
     * has(teamOne, teamTwo) will yield the same result as has(teamTwo, teamOne)
     */
    public function has(TeamId $teamOne, TeamId $teamTwo): bool
    {
        return $this->repository->has($this->prepareKey($teamOne, $teamTwo));
    }

    private function prepareKey(TeamId $teamOne, TeamId $teamTwo): string
    {
        //Ids are always stored in the format high:low
        // to ensure that results are same regardless of the order
        // the team ids are placed.
        $key = collect([$teamOne->toInt(), $teamTwo->toInt()])->sortDesc()->implode(':');

        return new CachePrefix($this) . $key;
    }

    public function put(TeamsHeadToHead $headToHead, TimeToLive $timeToLive): bool
    {
        return $this->repository->put(
            $this->prepareKey($headToHead->getTeamOneId(), $headToHead->getTeamTwoId()),
            $headToHead,
            $timeToLive->ttl()
        );
    }

    /**
     * The order of the team ids does not matter.
     * get(teamOne, teamTwo) will yield the same result as get(teamTwo, teamOne)
     *
     * @throws \App\Exceptions\ItemNotInCacheException
     */
    public function get(TeamId $teamOne, TeamId $teamTwo): TeamsHeadToHead
    {
        return $this->repository->get($this->prepareKey($teamOne, $teamTwo), fn () => throw new \App\Exceptions\ItemNotInCacheException);
    }
}
