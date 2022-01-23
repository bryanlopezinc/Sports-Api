<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\Utils\PaginationData;
use Module\User\ValueObjects\UserId;

final class FetchFavouritesController
{
    public function forGuest(UserFavouritesRequest $request, FetchUserFavourites $service): UserFavouritesResource
    {
        return new UserFavouritesResource($service->fromRequest($request));
    }

    public function auth(UserFavouritesRequest $request, FetchUserFavourites $service): UserFavouritesResource
    {
        return new UserFavouritesResource(
            $service->get(UserId::fromAuthUser(), PaginationData::fromRequest($request))
        );
    }
}
