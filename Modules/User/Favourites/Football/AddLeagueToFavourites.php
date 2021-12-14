<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football;

use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\FetchLeagueService;
use Module\User\Favourites\Exceptions\DuplicateEntryException;
use Module\User\Favourites\Exceptions\FavouriteAlreadyExistsHttpException;
use Module\User\Favourites\Exceptions\CannotAddMissingResourceToFavouritesHttpException;

final class AddLeagueToFavourites
{
    public function __construct(
        private FetchLeagueService $fetchLeagueService,
        private FavouritesRepository $repository
    ) {
    }

    public function create(LeagueId $id, UserId $userId): bool
    {
        throw_if(!$this->fetchLeagueService->leagueExists($id), new CannotAddMissingResourceToFavouritesHttpException);

        try {
            return $this->repository->addLeague($id, $userId);
        } catch (DuplicateEntryException) {
            throw new FavouriteAlreadyExistsHttpException;
        }
    }
}
