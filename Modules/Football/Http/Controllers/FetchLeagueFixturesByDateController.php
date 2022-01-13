<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\ValueObjects\Date;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\FetchLeagueFixturesByDateService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Http\Requests\FetchLeagueFixturesByDateRequest;
use Module\Football\Http\Resources\FixtureResource;
use Module\Football\Http\Resources\PartialFixtureResource;

final class FetchLeagueFixturesByDateController
{
    public function __invoke(FetchLeagueFixturesByDateRequest $request, FetchLeagueFixturesByDateService $service): AnonymousResourceCollection
    {
        $fixtures = $service->fetch(
            LeagueId::fromRequest($request, 'league_id'),
            new Date($request->get('date')),
            Season::fromString($request->input('season'))
        );

        $resource = PartialFixtureResource::collection(FixtureResource::collection($fixtures->toLaravelCollection()));

        return tap($resource, function (AnonymousResourceCollection $value): void {
            $value->collection = $value->collection->map(function (PartialFixtureResource $resource) {
                return $resource->setFilterInputName('filter');
            });
        });
    }
}
