<?php

declare(strict_types=1);

namespace Module\Football\Attributes;

use Attribute;
use Module\Football\ValueObjects\CoachCareer;
use App\Contracts\AfterMakingValidatorInterface;
use Module\Football\Collections\CoachCareerHistory;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureOnlyOneTeamIsCurrentTeam implements AfterMakingValidatorInterface
{
    /**
     * @param CoachCareerHistory $collection
     */
    public function validate(Object $collection): void
    {
        $currentTeamsCount = $collection
            ->toLaravelCollection()
            ->filter(fn (CoachCareer $career): bool => $career->isTeamCurrentManger())
            ->count();

        if ($currentTeamsCount > 1) {
            throw new \LogicException('Invalid coach career history'. 'Duplicate cuurent teams');
        }
    }
}
