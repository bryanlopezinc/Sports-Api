<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Utils\Config;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Services\FetchTeamService;
use Module\Football\Http\Resources\TeamResource;
use Module\Football\Http\Requests\FetchTeamRequest;

final class FetchTeamConttroller extends Controller
{
    public function __invoke(FetchTeamRequest $request, FetchTeamService $service): JsonResponse
    {
        return response()
            ->json(new TeamResource($service->findById(TeamId::fromRequest($request))))
            ->header('max-age', Config::get('football.findTeamResponseMaxAge'));
    }
}
