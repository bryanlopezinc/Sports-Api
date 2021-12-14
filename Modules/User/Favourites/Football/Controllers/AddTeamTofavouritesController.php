<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football\Controllers;

use Illuminate\Http\JsonResponse;
use Module\Football\ValueObjects\TeamId;
use Module\User\Dto\Builders\UserBuilder;
use Module\User\Favourites\Football\AddTeamToFavourites;
use Module\User\Favourites\Football\AddResourceToFavouritesRequest;

final class AddTeamTofavouritesController
{
    public function __invoke(AddResourceToFavouritesRequest $request, AddTeamToFavourites $service): JsonResponse
    {
        $service->create(TeamId::fromRequest($request), UserBuilder::fromAuthUser()->build()->getId());

        return response()->json(['success'], 201);
    }
}
