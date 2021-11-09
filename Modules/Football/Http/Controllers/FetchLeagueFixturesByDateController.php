<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\ValueObjects\Date;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Http\Resources\FixtureResource;
use Module\Football\Services\FetchLeagueFixturesByDateService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Http\Requests\FetchLeagueFixturesByDateRequest;

final class FetchLeagueFixturesByDateController
{
    public function __invoke(FetchLeagueFixturesByDateRequest $request, FetchLeagueFixturesByDateService $service): AnonymousResourceCollection
    {
        $fixtures = $service->fetch(
            LeagueId::fromRequest($request, 'league_id'),
            new Date($request->get('date')),
            Season::fromString($request->input('season'))
        );

        return FixtureResource::collection($fixtures->toLaravelCollection());
    }
}
