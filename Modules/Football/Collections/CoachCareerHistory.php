<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\ValueObjects\CoachCareer;
use Module\Football\Attributes\EnsureOnlyOneTeamIsCurrentTeam;

#[EnsureOnlyOneTeamIsCurrentTeam]
final class CoachCareerHistory extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof CoachCareer;
    }
}
