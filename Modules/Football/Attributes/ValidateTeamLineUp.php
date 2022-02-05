<?php

declare(strict_types=1);

namespace Module\Football\Attributes;

use Attribute;
use Module\Football\DTO\TeamLineUp;
use Module\Football\TeamMissingPlayer;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class ValidateTeamLineUp implements AfterMakingValidatorInterface
{
    /**
     * @param TeamLineUp $teamLineUp
     */
    public function validate(Object $teamLineUp): void
    {
        $startingPlayersCountEqualsEleven = $teamLineUp->getStartingEleven()->count() === 11;

        if (!$startingPlayersCountEqualsEleven) {
            throw new \LogicException('Team starting Eleven must have only eleven players');
        }

        $this->ensureContainsUniquePlayers($teamLineUp);
    }

    private function ensureContainsUniquePlayers(TeamLineUp $teamLineUp): void
    {
        $teamLineUp->getMissingPlayers()
            ->toLaravelCollection()
            ->duplicates(fn (TeamMissingPlayer $player) => $player->player()->getId()->toInt())
            ->whenNotEmpty(fn () => throw new \LogicException('Fixture missing players Collection can contain only unique players', 1220));
    }
}
