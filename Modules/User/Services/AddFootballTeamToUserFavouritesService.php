<?php

declare(strict_types=1);

namespace Module\User\Services;

use Module\User\Dto\UserFavourite;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Services\FetchTeamService;
use Module\User\Exceptions\UserFavouriteAlreadyExistsHttpException;
use Module\User\Exceptions\CannotAddMissingResourceToFavouritesHttpException;
use Module\User\Contracts\CreateUserFavouriteRepositoryInterface as Repository;

final class AddFootballTeamToUserFavouritesService
{
    public function __construct(
        private FetchTeamService $fetchTeamService,
        private Repository $repository
    ) {
    }

    public function create(UserFavourite $favourite): bool
    {
        $this->ensureTeamExists(new TeamId($favourite->favouriteId()->toInt()));

        $this->ensureUserDoesNotHaveFavourite($favourite);

        return $this->repository->create($favourite);
    }

    private function ensureTeamExists(TeamId $id): void
    {
        throw_if(!$this->fetchTeamService->exists($id), new CannotAddMissingResourceToFavouritesHttpException);
    }

    private function ensureUserDoesNotHaveFavourite(UserFavourite $favourite): void
    {
        if ($this->repository->exists($favourite)) {
            throw new UserFavouriteAlreadyExistsHttpException;
        }
    }
}
