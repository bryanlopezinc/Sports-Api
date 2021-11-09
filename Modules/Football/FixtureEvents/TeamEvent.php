<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TimeElapsed;

final class TeamEvent
{
    public function __construct(private Team $team, private TimeElapsed $time)
    {
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function time(): TimeElapsed
    {
        return $this->time;
    }
}
