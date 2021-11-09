<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\LeagueSeason;
use Module\Football\DTO\Builders\LeagueSeasonBuilder;
use Module\Football\Collections\LeagueSeasonsCollection;

final class LeagueSeasonFactory extends Factory
{
    protected string $dtoClass = LeagueSeason::class;

    public function definition()
    {
        return (new LeagueSeasonBuilder)
            ->setIsCurrentSeason(true)
            ->setDuration((string)now()->subWeek()->toDateString(), (string)now()->addWeek()->toDateString())
            ->setSeason(now()->year)
            ->setCoverage(LeagueCoverageFactory::new()->toDto())
            ->toArray();
    }

    public function toDto(): LeagueSeason
    {
        return $this->mapToDto();
    }

    public function toCollection(): LeagueSeasonsCollection
    {
        return $this->mapToCollection(LeagueSeasonsCollection::class);
    }
}
