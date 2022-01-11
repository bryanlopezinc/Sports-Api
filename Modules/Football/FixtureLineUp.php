<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\DTO\TeamLineUp;

final class FixtureLineUp
{
    public function __construct(private TeamLineUp $homeTeam, private TeamLineUp $awayTeam)
    {
        if ($this->isEmpty()) {
            return;
        }

        $this->ensureTeamsDontHaveSameCoach();
    }

    public function isEmpty(): bool
    {
        return $this->awayTeam->isEmpty() && $this->homeTeam->isEmpty();
    }

    private function ensureTeamsDontHaveSameCoach(): void
    {
        $teamsHaveSameCoach = $this->homeTeam->getCoach()->id()->equals($this->awayTeam->getCoach()->id());

        if ($teamsHaveSameCoach) {
            throw new \LogicException('Teams Cannot have same Coach', 3000);
        }
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
