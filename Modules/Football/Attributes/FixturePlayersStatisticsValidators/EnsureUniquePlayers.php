<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixturePlayersStatisticsValidators;

use Attribute;
use Module\Football\DTO\PlayerStatistics;
use App\Contracts\AfterMakingValidatorInterface;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureUniquePlayers implements AfterMakingValidatorInterface
{
    /**
     * @param FixturePlayersStatisticsCollection $collection
     */
    public function validate(Object $collection): void
    {
        $collection
            ->toLaravelCollection()
            ->duplicates(fn (PlayerStatistics $stats): int => $stats->player()->getId()->toInt())
            ->whenNotEmpty(fn () => throw new \LogicException('PlayerStatistics Collection Can contain unique players', 4000));
    }
}
