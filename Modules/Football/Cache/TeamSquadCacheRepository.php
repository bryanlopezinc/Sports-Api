<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Module\Football\ValueObjects\TeamId;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Contracts\Repositories\FetchTeamSquadRepositoryInterface;

final class TeamSquadCacheRepository implements FetchTeamSquadRepositoryInterface
{
    public function __construct(private Repository $repository, private FetchTeamSquadRepositoryInterface $fetchTeamSquadRepository)
    {
    }

    public function teamSquad(TeamId $teamId): PlayersCollection
    {
        $key = new CachePrefix($this) . $teamId->toInt();

        $ttl = now()->addDays(7);

        return $this->repository->remember($key, $ttl, fn () => $this->fetchTeamSquadRepository->teamSquad($teamId));
    }
}
