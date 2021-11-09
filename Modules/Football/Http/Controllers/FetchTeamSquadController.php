<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Utils\Config;
use Illuminate\Http\JsonResponse;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Http\Requests\FetchTeamRequest;
use Module\Football\Services\FetchTeamSquadService;
use Module\Football\Http\Resources\TeamSquadResource;

final class FetchTeamSquadController
{
    public function __invoke(FetchTeamRequest $request, FetchTeamSquadService $service): JsonResponse
    {
        return response()
            ->json(new TeamSquadResource($service->fetch(TeamId::fromRequest($request))))
            ->header('max-age', Config::get('football.teamSquadResponseMaxAge'));
    }
}
