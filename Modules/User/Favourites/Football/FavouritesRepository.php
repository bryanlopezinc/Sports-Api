<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football;

use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\LeagueId;
use Module\User\Favourites\Models\FavouriteType;
use Module\User\Favourites\Exceptions\DuplicateEntryException;
use Module\User\Favourites\FavouritesRepository as Repository;

final class FavouritesRepository
{
    public function __construct(private Repository $repository)
    {
    }

    /**
     * @throws DuplicateEntryException
     */
    public function addTeam(TeamId $teamId, UserId $userId): bool
    {
        $typeId = FavouriteType::where([
            'type'        => FavouriteType::TEAM_TYPE,
            'sports_type' => FavouriteType::SPORTS_TYPE_FOOTBALL,
        ])->first()->id;

        return $this->repository->create($userId, $typeId, $teamId);
    }

    /**
     * @throws DuplicateEntryException
     */
    public function addLeague(LeagueId $leagueId, UserId $userId): bool
    {
        $typeId = FavouriteType::where([
            'type'        => FavouriteType::LEAGUE_TYPE,
            'sports_type' => FavouriteType::SPORTS_TYPE_FOOTBALL,
        ])->first()->id;

        return $this->repository->create($userId, $typeId, $leagueId);
    }
}
