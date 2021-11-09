<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use Module\Football\DTO\League;
use App\Collections\DtoCollection;
use Module\Football\DTO\LeagueStanding;
use Module\Football\Attributes\LeagueTableValidators\EnsureStandingsHaveSameLeague;
use Module\Football\Attributes\LeagueTableValidators\EnsureRanksAreInConsecutiveOrder;

/**
 * @template T of LeagueStanding
 */
#[EnsureStandingsHaveSameLeague]
#[EnsureRanksAreInConsecutiveOrder]
final class LeagueTable extends DtoCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof LeagueStanding;
    }

    public function getLeague(): League
    {
        return $this->collection
            ->map(fn (LeagueStanding $leagueStanding) => $leagueStanding->getLeague())
            ->unique()
            ->sole();
    }
}
