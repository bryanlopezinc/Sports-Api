<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football\Controllers;

use Illuminate\Http\JsonResponse;
use Module\Football\ValueObjects\TeamId;
use Module\User\Favourites\Football\AddTeamToFavourites;
use Module\User\Favourites\Football\AddResourceToFavouritesRequest;
use Module\User\ValueObjects\UserId;

final class AddTeamTofavouritesController
{
    public function __invoke(AddResourceToFavouritesRequest $request, AddTeamToFavourites $service): JsonResponse
    {
        $service->create(TeamId::fromRequest($request), UserId::fromAuthUser());

        return response()->json(['success'], 201);
    }
}
