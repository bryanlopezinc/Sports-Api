<?php

declare(strict_types=1);

namespace Module\Football\Attributes\LeagueStandingValidators;

use Attribute;
use LogicException;
use Module\Football\DTO\StandingData;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureTotalGamesPlayedEqualsRecord implements AfterMakingValidatorInterface
{
    /**
     * @param StandingData $standing
     */
    public function validate(Object $standing): void
    {
        $expectedTotal = $standing->getTotalWins() + $standing->getTotalDraws() + $standing->getTotalLoses();

        if ($standing->getPlayed() !== $expectedTotal) {
            throw new LogicException(
                sprintf('Expected standing data total %s got %s', $expectedTotal, $standing->getPlayed()),
            );
        }
    }
}
