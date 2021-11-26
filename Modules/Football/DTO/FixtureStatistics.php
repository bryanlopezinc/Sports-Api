<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\FixtureStatistic\BallPossession;

final class FixtureStatistics extends DataTransferObject
{
    protected BallPossession $ballPossesion;
    protected int $shotsOnGoal;
    protected int $shotsInsideBox;
    protected int $shotsOutsideBox;
    protected int $totalShots;
    protected int $blockedShots;
    protected int $fouls;
    protected int $cornerKicks;
    protected int $offsides;
    protected int $yellowCards;
    protected int $redCards;
    protected int $goalKeeperSaves;
    protected int $passes;
    protected int $shotsOffGoal;
    protected int $accuratePaases;
    protected Team $team;

    public function team(): Team
    {
        return $this->team;
    }

    public function shotsOutsideBox(): int
    {
        return $this->shotsOutsideBox;
    }

    public function accuratePasses(): int
    {
        return $this->accuratePaases;
    }

    public function shotsOffGoal(): int
    {
        return $this->shotsOffGoal;
    }

    public function passes(): int
    {
        return $this->passes;
    }

    public function goalKeeperSaves(): int
    {
        return $this->goalKeeperSaves;
    }

    public function redCards(): int
    {
        return $this->redCards;
    }

    public function yellowCards(): int
    {
        return $this->yellowCards;
    }

    public function offsides(): int
    {
        return $this->offsides;
    }

    public function cornerKicks(): int
    {
        return $this->cornerKicks;
    }

    public function fouls(): int
    {
        return $this->fouls;
    }

    public function blockedShots(): int
    {
        return $this->blockedShots;
    }

    public function totalShots(): int
    {
        return $this->totalShots;
    }

    public function shotsInsideBox(): int
    {
        return $this->shotsInsideBox;
    }

    public function shotsOnGoal(): int
    {
        return $this->shotsOnGoal;
    }

    public function ballPossession(): BallPossession
    {
        return $this->ballPossesion;
    }
}