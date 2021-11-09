<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Module\User\Dto\Builders\UserBuilder;
use Module\Football\ValueObjects\LeagueId;
use Module\User\ValueObjects\UserFavouriteType;
use Module\User\Dto\Builders\UserFavouriteBuilder;
use Module\User\ValueObjects\UserFavouriteSportsType;
use Module\User\Http\Requests\AddUserFavouriteRequest;
use Module\User\Services\AddFootballLeagueToUserFavouritesService;

final class AddFootballLeagueToUserFavouritesController
{
    public function __invoke(AddUserFavouriteRequest $request, AddFootballLeagueToUserFavouritesService $service): JsonResponse
    {
        $userFavourite = (new UserFavouriteBuilder)
            ->setType(UserFavouriteType::LEAGUE)
            ->setSportsType(UserFavouriteSportsType::FOOTBALL)
            ->setFavouriteId(LeagueId::fromRequest($request)->toInt())
            ->setUserId(UserBuilder::fromAuthUser()->build()->getId()->toInt())
            ->build();

        $service->create($userFavourite);

        return response()->json(['success'], 201);
    }
}
