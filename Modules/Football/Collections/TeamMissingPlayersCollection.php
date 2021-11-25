<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\TeamMissingPlayer;

final class TeamMissingPlayersCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof TeamMissingPlayer;
    }
}
