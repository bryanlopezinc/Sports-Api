<?php

declare(strict_types=1);

namespace Module\Football\Collections;

use App\Collections\BaseCollection;
use Module\Football\DTO\League;
use Illuminate\Support\Collection;
use Module\Football\DTO\LeagueStanding;

final class LeagueTable extends BaseCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof LeagueStanding;
    }

    protected function validateItems(): void
    {
        parent::validateItems();

        if ($this->isEmpty()) {
            return;
        }

        $hasSameLeague = $this
            ->toLaravelCollection()
            ->map(fn (LeagueStanding $standing): int => $standing->getLeague()->getId()->toInt())
            ->unique()
            ->count() === 1;

        if (!$hasSameLeague) {
            throw new \InvalidArgumentException('league standing must have same league ids');
        }
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

    public function onlyTeams(TeamIdsCollection $teamIds): LeagueTable
    {
        return $this->collection
            ->filter(fn (LeagueStanding $leagueStanding) => $teamIds->has($leagueStanding->getTeam()->getId()))
            ->pipe(fn (Collection $collection) => new LeagueTable($collection->all()));
    }
}
