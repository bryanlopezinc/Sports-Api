<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Services;

use App\ValueObjects\Uid;
use Illuminate\Contracts\Events\Dispatcher;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Favourites\Repository;
use Module\Football\Services\FetchTeamService;
use Module\Football\Favourites\Exceptions\DuplicateEntryException;
use Module\Football\Favourites\Exceptions\FavouriteAlreadyExistsHttpException;
use Module\Football\Favourites\Exceptions\CannotAddMissingResourceToFavouritesHttpException;
use Module\User\Favourites\ResourceAddedToFavouritesEvent;

final class AddTeamToFavouritesService
{
    public function __construct(
        private FetchTeamService $fetchTeamService,
        private Repository $repository,
        private Dispatcher $dispatcher
    ) {
    }

    public function create(TeamId $teamId, UserId $userId): bool
    {
        throw_if(!$this->fetchTeamService->exists($teamId), new CannotAddMissingResourceToFavouritesHttpException);

        try {
            $this->repository->addTeam($teamId, $userId, $uid = Uid::generate());

            $this->dispatcher->dispatch(new ResourceAddedToFavouritesEvent($userId, $uid));

            return true;
        } catch (DuplicateEntryException) {
            throw new FavouriteAlreadyExistsHttpException;
        }
    }
}
