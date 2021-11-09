<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\ValueObjects\TeamFormation;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Attributes\FixtureLineUpValidators\EnsureTeamStartingElevenEqualsEleven;

#[EnsureTeamStartingElevenEqualsEleven]
final class TeamLineUp extends DataTransferObject
{
    protected PlayersCollection $startXI;
    protected TeamFormation $formation;
    protected PlayersCollection $substitutes;
    protected Team $team;
    protected Coach $coach;

    public function getStartingEleven(): PlayersCollection
    {
        return $this->startXI;
    }

    public function getFormation(): TeamFormation
    {
        return $this->formation;
    }

    public function getSubstitutes(): PlayersCollection
    {
        return $this->substitutes;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getCoach(): Coach
    {
        return $this->coach;
    }
}
