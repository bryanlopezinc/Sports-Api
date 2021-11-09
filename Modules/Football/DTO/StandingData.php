<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\Attributes\LeagueStandingValidators\EnsureTotalGamesPlayedEqualsRecord;

#[EnsureTotalGamesPlayedEqualsRecord]
final class StandingData extends DataTransferObject
{
    protected int $played;
    protected int $win;
    protected int $lose;
    protected int $draw;
    protected int $goals_for;
    protected int $goals_against;

    public function getPlayed(): int
    {
        return $this->played;
    }

    public function getTotalWins(): int
    {
        return $this->win;
    }

    public function getTotalLoses(): int
    {
        return $this->lose;
    }

    public function getTotalDraws(): int
    {
        return $this->draw;
    }

    public function getTotalGoalsScored(): int
    {
        return $this->goals_for;
    }

    public function getTotalGoalsConceeded(): int
    {
        return $this->goals_against;
    }
}