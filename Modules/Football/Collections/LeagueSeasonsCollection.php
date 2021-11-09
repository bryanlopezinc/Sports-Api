<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\LeagueSeason;
use Module\Football\ValueObjects\Season;

/**
 * @template T of LeagueSeason
 */
final class LeagueSeasonsCollection extends BaseCollection
{
    public function isValid(mixed $value): bool
    {
        return $value instanceof LeagueSeason;
    }

    public function anySeasonIsCurrent(): bool
    {
        /** @var LeagueSeason */
        foreach ($this->collection as $leagueSeaon) {
            if ($leagueSeaon->isCurrentSeason()) {
                return true;
            }
        }

        return false;
    }

    public function currentSeason(): LeagueSeason
    {
        return $this->collection->filter(fn (LeagueSeason $season) => $season->isCurrentSeason())->sole();
    }

    public function whereEquals(Season $season): LeagueSeason
    {
        return $this->collection->filter(fn (LeagueSeason $leagueSeason) => $season->equals($leagueSeason->getSeason()))->sole();
    }

    public function mostRecentSeason(): LeagueSeason
    {
        return $this->collection->sortByDesc(fn (LeagueSeason $season) => $season->getSeason()->toInt())->first();
    }

    public function push(LeagueSeason $leagueSeason): LeagueSeasonsCollection
    {
        return new LeagueSeasonsCollection(
            $this->collection->push($leagueSeason)
        );
    }

    public function has(LeagueSeason $leagueSeason): bool
    {
        /** @var LeagueSeason */
        foreach ($this->collection as $season) {
            if ($season->getSeason()->equals($leagueSeason->getSeason())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws \OutOfBoundsException
     * @throws \Illuminate\Collections\MultipleItemsFoundException
     */
    public function sole(): LeagueSeason
    {
        return $this->soleItem();
    }
}
