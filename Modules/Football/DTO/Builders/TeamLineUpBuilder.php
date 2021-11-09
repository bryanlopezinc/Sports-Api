<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\Team;
use Module\Football\DTO\Coach;
use Module\Football\DTO\TeamLineUp;
use Module\Football\ValueObjects\TeamFormation;
use Module\Football\Collections\PlayersCollection;

final class TeamLineUpBuilder extends Builder
{
    public function setStartingEleven(PlayersCollection $players): self
    {
        return $this->set('startXI', $players);
    }

    public function setFormation(TeamFormation $formation): self
    {
        return $this->set('formation', $formation);
    }

    public function setSubstitutes(PlayersCollection $players): self
    {
        return $this->set('substitutes', $players);
    }

    public function setTeam(Team $team): self
    {
        return $this->set('team', $team);
    }

    public function setCoach(Coach $coach): self
    {
        return $this->set('coach', $coach);
    }

    public function build(): TeamLineUp
    {
        return new TeamLineUp($this->toArray());
    }
}
