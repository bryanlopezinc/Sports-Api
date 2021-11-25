<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixtureLineUpValidators;

use Attribute;
use Module\Football\DTO\TeamLineUp;
use Module\Football\TeamMissingPlayer;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureContainsUniquePlayers implements AfterMakingValidatorInterface
{
    /**
     * @param TeamLineUp $teamLineUp
     */
    public function validate(Object $teamLineUp): void
    {
        $teamLineUp
            ->getMissingPlayers()
            ->toLaravelCollection()
            ->duplicates(fn (TeamMissingPlayer $player) => $player->player()->getId()->toInt())
            ->whenNotEmpty(fn () => throw new \LogicException('Fixture missing players Collection can contain only unique players', 1220));
    }
}
