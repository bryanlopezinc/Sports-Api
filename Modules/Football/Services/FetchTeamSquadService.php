<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\Config;
use App\Utils\TimeToLive;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Contracts\Cache\TeamsSquadsCacheInterface;
use Module\Football\Contracts\Repositories\FetchTeamSquadRepositoryInterface;

final class FetchTeamSquadService
{
    public function __construct(private FetchTeamSquadRepositoryInterface $repository, private TeamsSquadsCacheInterface $cache)
    {
    }

    public function fetch(TeamId $teamId): PlayersCollection
    {
        if ($this->cache->has($teamId)) {
            return $this->cache->getTeamSquad($teamId);
        }

        $teamSquad = $this->repository->teamSquad($teamId);

        $this->cache->cache($teamId, $teamSquad, TimeToLive::days(Config::get('football.cache.teamsSquad.ttl')));

        return $teamSquad;
    }
}
