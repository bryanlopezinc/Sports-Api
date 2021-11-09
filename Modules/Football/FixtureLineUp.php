<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\DTO\TeamLineUp;

final class FixtureLineUp
{
    public function __construct(private TeamLineUp $homeTeam, private TeamLineUp $awayTeam)
    {
    }

    public function homeTeam(): TeamLineUp
    {
        return $this->homeTeam;
    }

    public function awayTeam(): TeamLineUp
    {
        return $this->awayTeam;
    }
}
