<?php

declare(strict_types=1);

namespace Module\Football\FixtureStatistic;

use Illuminate\Support\Collection;
use Module\Football\TeamFixtureStatistics;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Contracts\FixtureStatisticInterface;

final class FixtureStatistics
{
    public function __construct(
        private FixtureId $fixtureId,
        private TeamFixtureStatistics $teamOne,
        private TeamFixtureStatistics $teamTwo
    ) {
        $this->ensureTeamsAreNotSame();
        $this->ensureTeamsHaveSameAmountOfStatisitcs();
        $this->ensureTeamsHaveSameStatisticsTypes();
        $this->ensureStatisticsDontHaveDuplicates();
        $this->ensureValidBallPossesionSpread();
    }

    private function ensureTeamsAreNotSame(): void
    {
        $teamsHasSameIds = $this->teamOne->team()->getId()->equals($this->teamTwo->team()->getId());

        if ($teamsHasSameIds) {
            throw new \LogicException('Teams must not be the same for fixture statistics', 422);
        }
    }

    private function ensureTeamsHaveSameAmountOfStatisitcs(): void
    {
        if ($this->teamOne()->statistics()->count() !== $this->teamTwo->statistics()->count()) {
            throw new \LogicException('Teams must have equal number of statistics', 423);
        }
    }

    private function ensureStatisticsDontHaveDuplicates(): void
    {
        $containsDuplicates = function (TeamFixtureStatistics $dto): bool {
            return $dto
                ->statistics()
                ->toLaravelCollection()
                ->duplicatesStrict(fn (FixtureStatisticInterface $stat) => $stat->name())
                ->isNotEmpty();
        };

        if ($containsDuplicates($this->teamOne) || $containsDuplicates($this->teamTwo)) {
            throw new \LogicException('Statistics Contains Duplicate values', 424);
        }
    }

    private function ensureTeamsHaveSameStatisticsTypes(): void
    {
        $mapWithStatName = function (TeamFixtureStatistics $dto): Collection {
            return  $dto
                ->statistics()
                ->toLaravelCollection()
                ->mapWithKeys(fn (FixtureStatisticInterface $stat): array => [$stat->name() => $stat->value()]);
        };

        $haveSameTypes = $mapWithStatName($this->teamOne)->diffKeys($mapWithStatName($this->teamTwo))->isEmpty();

        if (!$haveSameTypes) {
            throw new \LogicException('Teams must have same types statistics', 425);
        }
    }

    private function ensureValidBallPossesionSpread(): void
    {
        //Check if any team has ballPossesion statistic.
        //Doeson't really matter is it is checked with team one or team two since stats must
        //contain same types.
        if (!$this->teamOne->statistics()->hasBallPossesion()) {
            return;
        }

        $total = $this->teamOne->statistics()->ballPossesion()->value() + $this->teamTwo->statistics()->ballPossesion()->value();

        if ($total === 0) {
            return;
        }

        if ($total !== BallPossesion::MAX_VALUE) {
            throw new \LogicException(
                sprintf('Total ball possesion must be equal to %s, %s given', BallPossesion::MAX_VALUE, $total),
                426
            );
        }
    }

    public function fixtureId(): FixtureId
    {
        return $this->fixtureId;
    }

    public function teamOne(): TeamFixtureStatistics
    {
        return $this->teamOne;
    }

    public function teamTwo(): TeamFixtureStatistics
    {
        return $this->teamTwo;
    }
}
