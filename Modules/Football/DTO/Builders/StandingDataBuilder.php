<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\StandingData;

final class StandingDataBuilder extends Builder
{
    public function setMatchesPlayed(int $played): self
    {
        return $this->set('played', $played);
    }

    public function setMatchedWon(int $won): self
    {
        return $this->set('win', $won);
    }

    public function setMatchesLost(int $lost): self
    {
        return $this->set('lose', $lost);
    }

    public function setMatchesDrawn(int $draws): self
    {
        return $this->set('draw', $draws);
    }

    public function setGoalsFound(int $goals): self
    {
        return $this->set('goals_for', $goals);
    }

    public function setGoalsAgainst(int $goals): self
    {
        return $this->set('goals_against', $goals);
    }

    public function build(): StandingData
    {
        return new StandingData($this->attributes);
    }
}
