<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\LeagueSeason;
use Module\Football\DTO\LeagueCoverage;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueEndDate;
use Module\Football\ValueObjects\LeagueStartDate;
use Module\Football\ValueObjects\LeagueSeasonDuration;

final class LeagueSeasonBuilder extends Builder
{
    public static function fromLeagueSeason(LeagueSeason $leagueSeason): self
    {
        return new self($leagueSeason->toArray());
    }

    public function setDuration(string $startDate, string $endDate): self
    {
        return $this->set('duration', new LeagueSeasonDuration(
            new LeagueStartDate($startDate),
            new LeagueEndDate($endDate)
        ));
    }

    public function setCoverage(LeagueCoverage $leagueCoverage): self
    {
        return $this->set('coverage', $leagueCoverage);
    }

    public function setSeason(int $season): self
    {
        return $this->set('season', new Season($season));
    }

    public function setIsCurrentSeason(bool $isCurrentSeason): self
    {
        return $this->set('isCurrentSeason', $isCurrentSeason);
    }

    public function build(): LeagueSeason
    {
        return new LeagueSeason($this->toArray());
    }
}
