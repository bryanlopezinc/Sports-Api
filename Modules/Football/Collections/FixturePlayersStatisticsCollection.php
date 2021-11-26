<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\PlayerStatistics;
use Module\Football\Attributes\FixturePlayersStatisticsValidators\EnsureUniquePlayers;
use Module\Football\Attributes\FixturePlayersStatisticsValidators\EnsureContainsOneOrTwoTeams;

#[EnsureContainsOneOrTwoTeams]
#[EnsureUniquePlayers]
final class FixturePlayersStatisticsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof PlayerStatistics;
    }
}
