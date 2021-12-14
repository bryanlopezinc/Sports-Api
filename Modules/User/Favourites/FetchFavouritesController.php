<?php

declare(strict_types=1);

namespace Module\User\Favourites;

final class FetchFavouritesController
{
    public function __invoke(UserFavouritesRequest $request, FetchUserFavourites $service): UserFavouritesResource
    {
        return new UserFavouritesResource($service->fromRequest($request));
    }
}
