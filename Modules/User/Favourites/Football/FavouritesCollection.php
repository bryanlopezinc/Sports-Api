<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football;

use App\Collections\BaseCollection;
use Illuminate\Support\Collection;
use Module\User\Favourites\Dto\Favourite;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Collections\LeagueIdsCollection;

final class FavouritesCollection extends BaseCollection
{
    protected function isValid($value): bool
    {
        if (!$value instanceof Favourite) {
            return false;
        }

        return $value->sportType()->isFootball();
    }

    public function getTeamIds(): TeamIdsCollection
    {
        return $this->collection
            ->filter(fn (Favourite $favourite): bool => $favourite->getType()->isTeamType())
            ->map(fn (Favourite $favourite): TeamId => new TeamId($favourite->favouriteId()->toInt()))
            ->pipe(fn (Collection $collection): TeamIdsCollection => new TeamIdsCollection($collection->all()));
    }

    public function getLeagueIds(): LeagueIdsCollection
    {
        return $this->collection
            ->filter(fn (Favourite $favourite): bool => $favourite->getType()->isLeagueType())
            ->map(fn (Favourite $favourite): LeagueId => new LeagueId($favourite->favouriteId()->toInt()))
            ->pipe(fn (Collection $collection): LeagueIdsCollection => new  LeagueIdsCollection($collection->all()));
    }
}
