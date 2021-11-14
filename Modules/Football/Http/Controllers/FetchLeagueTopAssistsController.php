<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Http\Resources\LeagueTopAssistResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Services\FetchLeagueTopAssistsService as Service;
use Module\Football\Http\Requests\FetchLeagueTopAssistsRequest as Request;

final class FetchLeagueTopAssistsController
{
    public function __invoke(Request $request, Service $service): AnonymousResourceCollection
    {
        $response = $service->fetch(
            LeagueId::fromRequest($request),
            Season::fromString($request->input('season'))
        );

        return LeagueTopAssistResource::collection($response->toLaravelCollection());
    }
}