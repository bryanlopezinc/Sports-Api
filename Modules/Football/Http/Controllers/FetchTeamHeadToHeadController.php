<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Http\Requests\TeamsHeadToHeadRequest;
use Module\Football\Services\FetchTeamsHeadToHeadService;
use Module\Football\Http\Resources\PartialFixtureResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

final class FetchTeamHeadToHeadController
{
    public function __invoke(TeamsHeadToHeadRequest $request, FetchTeamsHeadToHeadService $service): AnonymousResourceCollection
    {
        $teams = [TeamId::fromRequest($request, 'team_id_1'), TeamId::fromRequest($request, 'team_id_2')];

        $fixtures = $service->fetch(...$teams);

        $fixtures = $fixtures
            ->toLaravelCollection()
            ->take((int)$request->input('limit', $fixtures->count()))
            ->pipe(fn (Collection $collection) => new FixturesCollection($collection->all()));

        $resourceCollection = PartialFixtureResource::collection($fixtures->toLaravelCollection());

        $resourceCollection->collection = $resourceCollection->collection->map(function (PartialFixtureResource $resource) {
            return $resource->setFilterInputName('fields');
        });

        return $resourceCollection;
    }
}
