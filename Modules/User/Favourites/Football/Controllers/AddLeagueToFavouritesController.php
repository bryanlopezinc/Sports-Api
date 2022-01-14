<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football\Controllers;

use Illuminate\Http\JsonResponse;
use Module\Football\ValueObjects\LeagueId;
use Module\User\Favourites\Football\AddLeagueToFavourites;
use Module\User\Favourites\Football\AddResourceToFavouritesRequest;
use Module\User\ValueObjects\UserId;

final class AddLeagueToFavouritesController
{
    public function __invoke(AddResourceToFavouritesRequest $request, AddLeagueToFavourites $service): JsonResponse
    {
        $service->create(LeagueId::fromRequest($request), UserId::fromAuthUser());

        return response()->json(['success'], 201);
    }
}
