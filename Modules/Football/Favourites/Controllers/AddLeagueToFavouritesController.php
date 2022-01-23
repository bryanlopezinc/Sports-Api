<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Controllers;

use Illuminate\Http\JsonResponse;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Favourites\Services\AddLeagueToFavouritesService;

final class AddLeagueToFavouritesController
{
    public function __invoke(Request $request, AddLeagueToFavouritesService $service): JsonResponse
    {
        $service->create(LeagueId::fromRequest($request), UserId::fromAuthUser());

        return response()->json(['success'], 201);
    }
}
