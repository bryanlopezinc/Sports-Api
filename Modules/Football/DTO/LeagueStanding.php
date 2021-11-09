<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\ValueObjects\TeamForm;

final class LeagueStanding extends DataTransferObject
{
    protected League $league;
    protected TeamForm $form;
    protected int $rank;
    protected Team $team;
    protected StandingData $allData;
    protected StandingData $home_record;
    protected StandingData $away_record;
    protected int $points;
    protected int $goalsDiff;
    protected string $description;
    protected bool $hasDescription;

    public function getLeague(): League
    {
        return $this->league;
    }

    public function getStandingRecord(): StandingData
    {
        return $this->allData;
    }

    public function getStandingHomeRecord(): StandingData
    {
        return $this->home_record;
    }

    public function getStandingAwayRecord(): StandingData
    {
        return $this->away_record;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getGoalsDifference(): int
    {
        return $this->goalsDiff;
    }

    public function getPositionDescription(): string
    {
        return $this->description;
    }

    public function hasPositionDescription(): bool
    {
        return $this->hasDescription;
    }

    public function getTeamCurrentForm(): TeamForm
    {
        return $this->form;
    }
}
