<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixturePlayersStatisticsValidators;

use Attribute;
use Module\Football\DTO\PlayerStatistics;
use App\Contracts\AfterMakingValidatorInterface;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureContainsOneOrTwoTeams implements AfterMakingValidatorInterface
{
    /**
     * @param FixturePlayersStatisticsCollection $collection
     */
    public function validate(Object $collection): void
    {
        $uniqueTeams = $collection
            ->toLaravelCollection()
            ->unique(fn (PlayerStatistics $stats): int => $stats->team()->getId()->toInt())
            ->count();

        if ($uniqueTeams > 2) {
            throw new \LogicException('PlayerStatistics Collection Can contain only two teams', 1500);
        }
    }
}
