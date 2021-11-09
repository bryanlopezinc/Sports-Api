<?php

declare(strict_types=1);

namespace Module\User\Services;

use Module\User\Dto\UserFavourite;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\FetchLeagueService;
use Module\User\Exceptions\UserFavouriteAlreadyExistsHttpException;
use Module\User\Exceptions\CannotAddMissingResourceToFavouritesHttpException;
use Module\User\Contracts\CreateUserFavouriteRepositoryInterface as Repository;

final class AddFootballLeagueToUserFavouritesService
{
    public function __construct(
        private FetchLeagueService $fetchLeagueService,
        private Repository $repository
    ) {
    }

    public function create(UserFavourite $favourite): bool
    {
        $this->ensureLeagueExists(new LeagueId($favourite->favouriteId()->toInt()));

        $this->ensureUserDoesHaveFavourite($favourite);

        return $this->repository->create($favourite);
    }

    private function ensureLeagueExists(LeagueId $id): void
    {
        throw_if(!$this->fetchLeagueService->leagueExists($id), new CannotAddMissingResourceToFavouritesHttpException);
    }

    private function ensureUserDoesHaveFavourite(UserFavourite $favourite): void
    {
        if ($this->repository->exists($favourite)) {
            throw new UserFavouriteAlreadyExistsHttpException;
        }
    }
}
