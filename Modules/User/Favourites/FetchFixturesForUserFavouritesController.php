<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\ValueObjects\Date;
use Module\User\ValueObjects\UserId;

final class FetchFixturesForUserFavouritesController
{
    public function __invoke(FixturesForUserFavouritesRepository $repository): UserFavouriteFixturesResource
    {
        return new UserFavouriteFixturesResource(
            $repository->fixtures(UserId::fromAuthUser(), new Date(today()->toDateString()))
        );
    }
}
