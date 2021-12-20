<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\League;
use Illuminate\Support\Collection;
use Module\Football\DTO\LeagueStanding;
use Module\Football\Attributes\LeagueTableValidators\EnsureStandingsHaveSameLeague;
use Module\Football\Attributes\LeagueTableValidators\EnsureRanksAreInConsecutiveOrder;

/**
 * @template T of LeagueStanding
 */
#[EnsureStandingsHaveSameLeague]
#[EnsureRanksAreInConsecutiveOrder]
final class LeagueTable extends BaseCollection
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

    public function teams(): TeamsCollection
    {
        return $this->collection
            ->map(fn (LeagueStanding $leagueStanding) => $leagueStanding->getTeam())
            ->pipe(fn (Collection $collection) => new TeamsCollection($collection->all()));
    }
}
