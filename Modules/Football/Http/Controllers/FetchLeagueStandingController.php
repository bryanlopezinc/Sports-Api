<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\FetchLeagueStandingService;
use Module\Football\Http\Resources\LeagueStandingResource;
use Module\Football\Http\Requests\FetchLeagueStandingRequest;

final class FetchLeagueStandingController
{
    public function __invoke(FetchLeagueStandingRequest $request, FetchLeagueStandingService $service): LeagueStandingResource
    {
        $leagueTable = $service->fetch(LeagueId::fromRequest($request, 'league_id'), Season::fromString($request->input('season')));

        return new LeagueStandingResource($leagueTable);
    }
}
