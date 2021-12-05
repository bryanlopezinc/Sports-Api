<?php

declare(strict_types=1);

namespace Module\Football\Attributes\LeagueStandingValidators;

use Attribute;
use Module\Football\DTO\StandingData;
use App\Contracts\AfterMakingValidatorInterface;
use App\ValueObjects\NonNegativeNumber;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureAllValuesAreNotNegative implements AfterMakingValidatorInterface
{
    /**
     * @param StandingData $standing
     */
    public function validate(Object $standing): void
    {
        NonNegativeNumber::check([
            $standing->getPlayed(),
            $standing->getTotalWins(),
            $standing->getTotalLoses(),
            $standing->getTotalDraws(),
            $standing->getTotalGoalsScored(),
            $standing->getTotalGoalsConceeded()
        ]);
    }
}
