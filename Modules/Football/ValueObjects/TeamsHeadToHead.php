<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use Module\Football\DTO\Fixture;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Collections\FixturesCollection;

final class TeamsHeadToHead
{
    public function __construct(
        private TeamId $teamOne,
        private TeamId $teamTwo,
        private FixturesCollection $headToHeadFixtures
    ) {
        $this->ensureTeamIdsAreNotSame();
        $this->ensureEachFixtureContainsOnlyHeadToHeadTeamIds();
    }

    private function ensureTeamIdsAreNotSame(): void
    {
        if ($this->teamOne->equals($this->teamTwo)) {
            throw new \InvalidArgumentException('Teams head to head must have different ids');
        }
    }

    private function ensureEachFixtureContainsOnlyHeadToHeadTeamIds(): void
    {
        $this->headToHeadFixtures
            ->toLaravelCollection()
            ->each(function (Fixture $fixture): void {
                $ids = new TeamIdsCollection([$fixture->getHomeTeam()->getId(), $fixture->getAwayTeam()->getId()]);

                $containsIds = $ids->has($this->teamOne) && $ids->has($this->teamTwo);

                if (!$containsIds) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'Expecting only ids %s and %s got %s',
                            $this->teamOne->toInt(),
                            $this->teamTwo->toInt(),
                            $fixture->id()->toInt()
                        )
                    );
                }
            });
    }

    public function getHeadToHeadFixtures(): FixturesCollection
    {
        return $this->headToHeadFixtures;
    }

    public function getTeamOneId(): TeamId
    {
        return $this->teamOne;
    }

    public function getTeamTwoId(): TeamId
    {
        return $this->teamTwo;
    }
}
