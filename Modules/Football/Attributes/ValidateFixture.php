<?php

declare(strict_types=1);

namespace Module\Football\Attributes;

use Attribute;
use Module\Football\DTO\Fixture;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class ValidateFixture implements AfterMakingValidatorInterface
{
    /**
     * @param Fixture $fixture
     */
    public function validate(Object $fixture): void
    {
        $this->ensureHomeAndAwayTeamsAreNotSame($fixture);
        $this->ensureWinnerIdBelongsToATeamInFixture($fixture);
    }

    private function ensureHomeAndAwayTeamsAreNotSame(Fixture $fixture): void
    {
        if ($fixture->getHomeTeam()->getId()->equals($fixture->getAwayTeam()->getId())) {
            throw new \LogicException('fixture cannot have same teams', 600);
        }
    }

    private function ensureWinnerIdBelongsToATeamInFixture(Fixture $fixture): void
    {
        if (!$fixture->hasWinner()) {
            return;
        }

        $winnerId = $fixture->winnerId();

        $idBelongsToATeamInFixture = $winnerId->equals($fixture->getHomeTeam()->getId()) || $winnerId->equals($fixture->getAwayTeam()->getId());

        if (!$idBelongsToATeamInFixture) {
            throw new \LogicException(
                sprintf(
                    'Fixture with id %s expects winner id to be %s or %s got %s',
                    $fixture->id()->toInt(),
                    $fixture->getHomeTeam()->getId()->toInt(),
                    $fixture->getAwayTeam()->getId()->toInt(),
                    $winnerId->toInt()
                ),
                601
            );
        }
    }
}
