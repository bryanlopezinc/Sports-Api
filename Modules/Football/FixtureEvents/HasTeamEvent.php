<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TimeElapsed;

trait HasTeamEvent
{
    public function team(): Team
    {
        return $this->teamEvent->getTeam();
    }

    public function time(): TimeElapsed
    {
        return $this->teamEvent->time();
    }
}
