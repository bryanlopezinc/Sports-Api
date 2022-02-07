<?php

declare(strict_types=1);

namespace Module\Football\Attributes;

use Attribute;
use Module\Football\DTO\StandingData;
use App\Contracts\AfterMakingValidatorInterface;
use App\ValueObjects\PositiveNumber;

#[Attribute(Attribute::TARGET_CLASS)]
final class ValidateLeagueStanding implements AfterMakingValidatorInterface
{
    /**
     * @param StandingData $standing
     */
    public function validate(Object $standing): void
    {
        $this->ensureStatisticsValuesAreNotNegative($standing);
        $this->ensureGamesPlayedTotalIsCorrect($standing);
    }

    private function ensureStatisticsValuesAreNotNegative(StandingData $standing): void
    {
        PositiveNumber::check([
            $standing->getPlayed(),
            $standing->getTotalWins(),
            $standing->getTotalLoses(),
            $standing->getTotalDraws(),
            $standing->getTotalGoalsScored(),
            $standing->getTotalGoalsConceeded()
        ]);
    }

    private function ensureGamesPlayedTotalIsCorrect(StandingData $standing): void
    {
        $expectedTotal = $standing->getTotalWins() + $standing->getTotalDraws() + $standing->getTotalLoses();

        if ($standing->getPlayed() !== $expectedTotal) {
            throw new \LogicException(
                sprintf('Expected standing data total %s got %s', $expectedTotal, $standing->getPlayed()),
            );
        }
    }
}
