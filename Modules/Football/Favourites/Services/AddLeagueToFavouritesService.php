<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Services;

use App\ValueObjects\Uid;
use Illuminate\Contracts\Events\Dispatcher;
use Module\User\ValueObjects\UserId;
use Module\Football\Favourites\Repository;
use Module\Football\Favourites\Exceptions\DuplicateEntryException;
use Module\Football\Favourites\Exceptions\FavouriteAlreadyExistsHttpException;
use Module\Football\Favourites\Exceptions\CannotAddMissingResourceToFavouritesHttpException;
use Module\User\Favourites\ResourceAddedToFavouritesEvent;
use Module\Football\Services\FetchLeagueService;
use Module\Football\ValueObjects\LeagueId;

final class AddLeagueToFavouritesService
{
    public function __construct(
        private FetchLeagueService $service,
        private Repository $repository,
        private Dispatcher $dispatcher
    ) {
    }

    public function create(LeagueId $leagueId, UserId $userId): bool
    {
        throw_if(!$this->service->leagueExists($leagueId), new CannotAddMissingResourceToFavouritesHttpException);

        try {
            $this->repository->addLeague($leagueId, $userId, $uid = Uid::generate());

            $this->dispatcher->dispatch(new ResourceAddedToFavouritesEvent($userId, $uid));

            return true;
        } catch (DuplicateEntryException) {
            throw new FavouriteAlreadyExistsHttpException;
        }
    }
}
