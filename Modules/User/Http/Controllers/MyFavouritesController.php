<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use App\Utils\PaginationData;
use Module\User\Dto\Builders\UserBuilder;
use Module\User\Services\FetchUserFavourites;
use Module\User\Http\Requests\UserFavouritesRequest;
use Module\User\Http\Resources\UserFavouritesResource;

final class MyFavouritesController
{
    public function __invoke(UserFavouritesRequest $request, FetchUserFavourites $service): UserFavouritesResource
    {
        return new UserFavouritesResource(
            $service->get(UserBuilder::fromAuthUser()->build()->getId(), PaginationData::fromRequest($request))
        );
    }
}
