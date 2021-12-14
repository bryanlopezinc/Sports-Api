<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\Utils\PaginationData;
use Module\User\Dto\Builders\UserBuilder;

final class MyFavouritesController
{
    public function __invoke(UserFavouritesRequest $request, FetchUserFavourites $service): UserFavouritesResource
    {
        return new UserFavouritesResource(
            $service->get(UserBuilder::fromAuthUser()->build()->getId(), PaginationData::fromRequest($request))
        );
    }
}
