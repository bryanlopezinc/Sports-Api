<?php

declare(strict_types=1);

namespace Module\Football\Collections\Validators;

use Module\Football\DTO\PlayerStatistics;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

final class ValidateFixturePlayersStatistics
{
    public function __construct(FixturePlayersStatisticsCollection $collection)
    {
        $this->validate($collection);
    }

    private function validate(FixturePlayersStatisticsCollection $collection): void
    {
        $this->ensureContainsOneOrTwoTeams($collection);
        $this->ensurePlayersAreUnique($collection);
    }

    private function ensureContainsOneOrTwoTeams(FixturePlayersStatisticsCollection $collection): void
    {
        $uniqueTeams = $collection
            ->toLaravelCollection()
            ->unique(fn (PlayerStatistics $stats): int => $stats->team()->getId()->toInt())
            ->count();

        if ($uniqueTeams > 2) {
            throw new \LogicException('PlayerStatistics Collection Can contain only two teams', 1500);
        }
    }

    private function ensurePlayersAreUnique(FixturePlayersStatisticsCollection $collection): void
    {
        $collection
            ->toLaravelCollection()
            ->duplicates(fn (PlayerStatistics $stats): int => $stats->player()->getId()->toInt())
            ->whenNotEmpty(fn () => throw new \LogicException('PlayerStatistics Collection Can contain unique players', 4000));
    }
}
