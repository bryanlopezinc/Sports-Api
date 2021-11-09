<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixtureValidators;

use Attribute;
use Module\Football\DTO\Fixture;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureWinnerIdBelongsToFixtureTeams implements AfterMakingValidatorInterface
{
    /**
     * @param Fixture $fixture
     */
    public function validate(Object $fixture): void
    {
        if (!$fixture->hasWinner()) {
            return;
        }

        $winnerId = $fixture->winnerId();

        $belongsToFixtureTeams = $winnerId->equals($fixture->getHomeTeam()->getId()) || $winnerId->equals($fixture->getAwayTeam()->getId());

        if (! $belongsToFixtureTeams) {
            throw new \LogicException(
                sprintf(
                    'Fixture with id %s expects winner id to be %s or %s got %s',
                    $fixture->id()->toInt(),
                    $fixture->getHomeTeam()->getId()->toInt(),
                    $fixture->getAwayTeam()->getId()->toInt(),
                    $winnerId->toInt()
                )
            );
        }
    }
}
