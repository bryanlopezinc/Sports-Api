<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\FetchLeagueService;
use Module\Football\Http\Requests\FetchLeagueRequest;
use Module\Football\Http\Resources\PartialLeagueResource;

final class FetchLeagueController
{
    public function __invoke(FetchLeagueRequest $request, FetchLeagueService $service): PartialLeagueResource
    {
        $leagueId = LeagueId::fromRequest($request);

        if ($request->has('season')) {
            $league = $service->findByIdAndSeason($leagueId, Season::fromString($request->input('season')));
        } else {
            $league = $service->findManyById($leagueId->toCollection())->sole();
        }

        return new PartialLeagueResource($league, 'filter');
    }
}
