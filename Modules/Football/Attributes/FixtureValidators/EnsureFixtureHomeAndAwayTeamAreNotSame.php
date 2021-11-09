<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixtureValidators;

use Attribute;
use Module\Football\DTO\Fixture;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureFixtureHomeAndAwayTeamAreNotSame implements AfterMakingValidatorInterface
{
    /**
     * @param Fixture $fixture
     */
    public function validate(Object $fixture): void
    {
        $homeAndAwayTeamHaveSameId = $fixture->getHomeTeam()->getId()->equals($fixture->getAwayTeam()->getId());

        if ($homeAndAwayTeamHaveSameId) {
            throw new \LogicException('fixture cannot have same teams');
        }
    }
}
