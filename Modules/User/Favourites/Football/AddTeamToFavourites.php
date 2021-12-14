<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football;

use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Services\FetchTeamService;
use Module\User\Favourites\Exceptions\DuplicateEntryException;
use Module\User\Favourites\Exceptions\FavouriteAlreadyExistsHttpException;
use Module\User\Favourites\Exceptions\CannotAddMissingResourceToFavouritesHttpException;

final class AddTeamToFavourites
{
    public function __construct(
        private FetchTeamService $fetchTeamService,
        private FavouritesRepository $repository
    ) {
    }

    public function create(TeamId $teamId, UserId $userId): bool
    {
        throw_if(!$this->fetchTeamService->exists($teamId), new CannotAddMissingResourceToFavouritesHttpException);

        try {
            return $this->repository->addTeam($teamId, $userId);
        } catch (DuplicateEntryException) {
            throw new FavouriteAlreadyExistsHttpException;
        }
    }
}
