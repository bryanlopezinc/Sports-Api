<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Utils\Config;
use Illuminate\Http\JsonResponse;
use Module\Football\Contracts\Repositories\FetchTeamSquadRepositoryInterface;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Http\Requests\FetchTeamRequest;
use Module\Football\Http\Resources\TeamSquadResource;

final class FetchTeamSquadController
{
    public function __invoke(FetchTeamRequest $request, FetchTeamSquadRepositoryInterface $repository): JsonResponse
    {
        return response()
            ->json(new TeamSquadResource($repository->teamSquad(TeamId::fromRequest($request))))
            ->header('max-age', Config::get('football.teamSquadResponseMaxAge'));
    }
}
