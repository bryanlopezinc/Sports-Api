<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\Team;
use Module\Football\DTO\FixtureStatistics;
use Module\Football\FixtureStatistic\BallPossession;
use App\DTO\Builder;

final class FixtureStatisticsBuilder extends Builder
{
    public function team(Team $team): self
    {
        return $this->set('team', $team);
    }

    public function shotsOffGoal(int $shotsOffGoal): self
    {
        return $this->set('shotsOffGoal', $shotsOffGoal);
    }

    public function accuratePasses(int $accuratePaases): self
    {
        return $this->set('accuratePaases', $accuratePaases);
    }

    public function passes(int $passes): self
    {
        return $this->set('passes', $passes);
    }

    public function goalKeeperSaves(int $goalKeeperSaves): self
    {
        return $this->set('goalKeeperSaves', $goalKeeperSaves);
    }

    public function redCards(int $redCards): self
    {
        return $this->set('redCards', $redCards);
    }

    public function yellowCards(int $yellowCards): self
    {
        return $this->set('yellowCards', $yellowCards);
    }

    public function offsides(int $offsides): self
    {
        return $this->set('offsides', $offsides);
    }

    public function cornerKicks(int $cornerKicks): self
    {
        return $this->set('cornerKicks', $cornerKicks);
    }

    public function fouls(int $fouls): self
    {
        return $this->set('fouls', $fouls);
    }

    public function blockedShots(int $blockedShots): self
    {
        return $this->set('blockedShots', $blockedShots);
    }

    public function totalShots(int $totalShots): self
    {
        return $this->set('totalShots', $totalShots);
    }

    public function shotsOutsideBox(int $shotsOutsideBox): self
    {
        return $this->set('shotsOutsideBox', $shotsOutsideBox);
    }

    public function shotsInsideBox(int $shotsInsideBox): self
    {
        return $this->set('shotsInsideBox', $shotsInsideBox);
    }

    public function shotsOnGoal(int $shotsOnGoal): self
    {
        return $this->set('shotsOnGoal', $shotsOnGoal);
    }

    public function possession(int $possession): self
    {
        return $this->set('ballPossesion', new BallPossession($possession));
    }

    public function build(): FixtureStatistics
    {
        return new FixtureStatistics($this->toArray());
    }
}
