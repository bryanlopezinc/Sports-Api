<?php

declare(strict_types=1);

namespace Module\Football\Services;

use Module\Football\Cache\TeamsHeadToHeadCacheRepository;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Repositories\FetchTeamHeadToHeadRepositoryInterface;
use Module\Football\TeamsHeadToHeadTTL;

final class FetchTeamsHeadToHeadService
{
    public function __construct(
        private FetchTeamHeadToHeadRepositoryInterface $repository,
        private TeamsHeadToHeadCacheRepository $cache,
    ) {
    }

    public function fetch(TeamId $temOne, TeamId $teamTwo): FixturesCollection
    {
        $teamIds  = [$temOne, $teamTwo];

        if ($this->cache->has(...$teamIds)) {
            return $this->cache->get(...$teamIds)->getHeadToHeadFixtures();
        }

        $teamsHeadToHead = $this->repository->getHeadToHead(...$teamIds);

        $fixtures = $teamsHeadToHead->getHeadToHeadFixtures();

        $this->cache->put($teamsHeadToHead, (new TeamsHeadToHeadTTL)($fixtures));

        return $fixtures;
    }
}
