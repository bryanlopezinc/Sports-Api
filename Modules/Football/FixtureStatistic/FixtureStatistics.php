<?php

declare(strict_types=1);

namespace Module\Football\FixtureStatistic;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\DTO\FixtureStatistics as FixtureStatisticsData;

final class FixtureStatistics
{
    public function __construct(
        private FixtureId $fixtureId,
        private FixtureStatisticsData $teamOne,
        private FixtureStatisticsData $teamTwo
    ) {
        $this->ensureTeamsAreNotSame();
        $this->ensureValidBallPossesionSpread();
    }

    private function ensureTeamsAreNotSame(): void
    {
        $teamsHasSameIds = $this->teamOne->team()->getId()->equals($this->teamTwo->team()->getId());

        if ($teamsHasSameIds) {
            throw new \LogicException('Teams must not be the same for fixture statistics', 422);
        }
    }

    private function ensureValidBallPossesionSpread(): void
    {
        $total = $this->teamOne->ballPossession()->value() + $this->teamTwo->ballPossession()->value();

        if ($total === 0) {
            return;
        }

        if ($total !== BallPossession::MAX_VALUE) {
            throw new \LogicException(
                sprintf('Total ball possesion must be equal to %s, %s given', BallPossession::MAX_VALUE, $total),
                426
            );
        }
    }

    public function fixtureId(): FixtureId
    {
        return $this->fixtureId;
    }

    public function teamOne(): FixtureStatisticsData
    {
        return $this->teamOne;
    }

    public function teamTwo(): FixtureStatisticsData
    {
        return $this->teamTwo;
    }
}
