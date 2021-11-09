<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Module\Football\ValueObjects\TeamId;
use Module\User\Dto\Builders\UserBuilder;
use Module\User\ValueObjects\UserFavouriteType;
use Module\User\Dto\Builders\UserFavouriteBuilder;
use Module\User\ValueObjects\UserFavouriteSportsType;
use Module\User\Http\Requests\AddUserFavouriteRequest;
use Module\User\Services\AddFootballTeamToUserFavouritesService;

final class AddFootballTeamToUserfavouritesController
{
    public function __invoke(AddUserFavouriteRequest $request, AddFootballTeamToUserFavouritesService $service): JsonResponse
    {
        $userFavourite = (new UserFavouriteBuilder)
            ->setType(UserFavouriteType::TEAM)
            ->setSportsType(UserFavouriteSportsType::FOOTBALL)
            ->setFavouriteId(TeamId::fromRequest($request)->toInt())
            ->setUserId(UserBuilder::fromAuthUser()->build()->getId()->toInt())
            ->build();

        $service->create($userFavourite);

        return response()->json(['success'], 201);
    }
}
