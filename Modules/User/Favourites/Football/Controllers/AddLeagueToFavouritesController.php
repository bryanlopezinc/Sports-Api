<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football\Controllers;

use Illuminate\Http\JsonResponse;
use Module\User\Dto\Builders\UserBuilder;
use Module\Football\ValueObjects\LeagueId;
use Module\User\Favourites\Football\AddLeagueToFavourites;
use Module\User\Favourites\Football\AddResourceToFavouritesRequest;

final class AddLeagueToFavouritesController
{
    public function __invoke(AddResourceToFavouritesRequest $request, AddLeagueToFavourites $service): JsonResponse
    {
        $service->create(LeagueId::fromRequest($request), UserBuilder::fromAuthUser()->build()->getId());

        return response()->json(['success'], 201);
    }
}
