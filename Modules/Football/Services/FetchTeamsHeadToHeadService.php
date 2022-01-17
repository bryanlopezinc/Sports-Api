<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\TimeToLive;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Cache\TeamsHeadToHeadCacheInterface;
use Module\Football\Contracts\Repositories\FetchTeamHeadToHeadRepositoryInterface;

final class FetchTeamsHeadToHeadService
{
    public function __construct(
        private FetchTeamHeadToHeadRepositoryInterface $repository,
        private TeamsHeadToHeadCacheInterface $cache,
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

        $this->cache->put($teamsHeadToHead, $this->determineTimeToLiveIncacheFrom($fixtures));

        return $fixtures;
    }

    private function determineTimeToLiveIncacheFrom(FixturesCollection $collection): TimeToLive
    {
        if ($collection->anyFixtureIsInProgress()) {
            return TimeToLive::seconds(120);
        }

        if ($collection->allFixturesArefinished()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        if (!$collection->hasUpcomingFixture()) {
            return TimeToLive::minutes(minutesUntilTommorow());
        }

        return TimeToLive::seconds(
            now()->diffInSeconds($collection->nextUpcomingFixture()->date()->toCarbon()->toDateTimeString())
        );
    }
}
