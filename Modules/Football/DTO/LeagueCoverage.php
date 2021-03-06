<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;

final class LeagueCoverage extends DataTransferObject
{
    protected bool $statistics;
    protected bool $coverslineUp;
    protected bool $coversEvents;
    protected bool $coverTopScorers;
    protected bool $coversTopAssists;
    protected bool $coversPlayerStatistics;
    protected bool $coversLeagueStanding;

    public function coversPlayerStatistics(): bool
    {
        return $this->coversPlayerStatistics;
    }

    public function coversStatistics(): bool
    {
        return $this->statistics;
    }

    public function coversTopAssists(): bool
    {
        return $this->coversTopAssists;
    }

    public function coversTopScorers(): bool
    {
        return $this->coverTopScorers;
    }

    public function coversLeagueStanding(): bool
    {
        return $this->coversLeagueStanding;
    }

    public function coverslineUp(): bool
    {
        return $this->coverslineUp;
    }

    public function coversEvents(): bool
    {
        return $this->coversEvents;
    }
}
