<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Controllers;

use Illuminate\Http\JsonResponse;
use Module\Football\Favourites\Services\AddTeamToFavouritesService;
use Module\Football\ValueObjects\TeamId;
use Module\User\ValueObjects\UserId;

final class AddTeamTofavouritesController
{
    public function __invoke(Request $request, AddTeamToFavouritesService $service): JsonResponse
    {
        $service->create(TeamId::fromRequest($request), UserId::fromAuthUser());

        return response()->json(['success'], 201);
    }
}
