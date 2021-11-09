<?php

declare(strict_types=1);

namespace Module\User\Collections;

use App\Collections\DtoCollection;
use Illuminate\Support\Collection;
use Module\User\Dto\UserFavourite;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Collections\LeagueIdsCollection;

final class UserFavouriteTypesCollection extends DtoCollection
{
    protected function isValid($value): bool
    {
        return $value instanceof UserFavourite;
    }

    public function getFootballTeamTypeIds(): TeamIdsCollection
    {
        return $this->collection
            ->filter(fn (UserFavourite $favourite): bool => $favourite->getType()->isTeamType() && $favourite->sportsType()->isFootballType())
            ->map(fn (UserFavourite $favourite): TeamId => new TeamId($favourite->favouriteId()->toInt()))
            ->pipe(fn (Collection $collection): TeamIdsCollection => new TeamIdsCollection($collection->all()));
    }

    public function getLeagueTypeIds(): LeagueIdsCollection
    {
        return $this->collection
            ->filter(fn (UserFavourite $favourite): bool => $favourite->getType()->isLeagueType() && $favourite->sportsType()->isFootballType())
            ->map(fn (UserFavourite $favourite): LeagueId => new LeagueId($favourite->favouriteId()->toInt()))
            ->pipe(fn (Collection $collection): LeagueIdsCollection => new  LeagueIdsCollection($collection->all()));
    }
}
