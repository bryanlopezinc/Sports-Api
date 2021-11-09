<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Module\User\Services\FetchUserFavourites;
use Module\User\Http\Requests\UserFavouritesRequest;
use Module\User\Http\Resources\UserFavouritesResource;

final class UserFavouritesController
{
    public function __invoke(UserFavouritesRequest $request, FetchUserFavourites $service): UserFavouritesResource
    {
        return new UserFavouritesResource($service->fromRequest($request));
    }
}
