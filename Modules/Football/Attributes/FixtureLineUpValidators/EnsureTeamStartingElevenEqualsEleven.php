<?php

declare(strict_types=1);

namespace Module\Football\Attributes\FixtureLineUpValidators;

use Attribute;
use LogicException;
use Module\Football\DTO\TeamLineUp;
use App\Contracts\AfterMakingValidatorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureTeamStartingElevenEqualsEleven implements AfterMakingValidatorInterface
{
    /**
     * @param TeamLineUp $lineUp
     */
    public function validate(Object $lineUp): void
    {
        if ($lineUp->getStartingEleven()->count() !== 11) {
            throw new LogicException('Team starting Eleven must have only eleven players');
        }
    }
}
