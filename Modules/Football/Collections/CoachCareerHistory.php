<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\ValueObjects\CoachCareer;

final class CoachCareerHistory extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof CoachCareer;
    }

    protected function validateItems(): void
    {
        parent::validateItems();

        $currentTeamsCount = $this->toLaravelCollection()->filter(fn (CoachCareer $career) => $career->isTeamCurrentManger())->count();

        if ($currentTeamsCount > 1) {
            throw new \LogicException('Invalid coach career history' . 'Duplicate cuurent teams');
        }
    }
}
