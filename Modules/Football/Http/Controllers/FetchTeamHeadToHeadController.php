<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\TeamId;
use Module\Football\Http\Resources\FixtureResource;
use Module\Football\Http\Requests\TeamsHeadToHeadRequest;
use Module\Football\Services\FetchTeamsHeadToHeadService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FetchTeamHeadToHeadController
{
    public function __invoke(TeamsHeadToHeadRequest $request, FetchTeamsHeadToHeadService $service): AnonymousResourceCollection
    {
        $teams = [TeamId::fromRequest($request, 'team_id_1'), TeamId::fromRequest($request, 'team_id_2')];

        return FixtureResource::collection($service->fetch(...$teams)->toLaravelCollection());
    }
}
