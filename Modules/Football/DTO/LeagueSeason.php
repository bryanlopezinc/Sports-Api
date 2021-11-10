<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueSeasonDuration;

final class LeagueSeason extends DataTransferObject
{
    protected Season $season;
    protected LeagueSeasonDuration $duration;
    protected bool $isCurrentSeason;
    protected LeagueCoverage $coverage;

    public function getCoverage(): LeagueCoverage
    {
        return $this->coverage;
    }

    public function isCurrentSeason(): bool
    {
        return $this->isCurrentSeason;
    }

    public function getDuration(): LeagueSeasonDuration
    {
        return $this->duration;
    }

    public function getSeason(): Season
    {
        return $this->season;
    }
}
