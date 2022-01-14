<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\Http\FetchFixtureResource\SetUserHasPredictionFixture;
use Module\Football\Http\FetchFixtureResource\SetUserPrediction;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Http\Requests\FetchFixtureRequest;
use Module\Football\Http\Resources\FixtureResource;
use Module\Football\Http\Resources\PartialLeagueResource;
use Module\Football\Http\Resources\PartialFixtureResource;

final class FetchFixtureController
{
    public function __invoke(FetchFixtureRequest $request, FetchFixtureService $service): PartialFixtureResource
    {
        $resource = new SetUserPrediction(
            new SetUserHasPredictionFixture(
                new FixtureResource($service->fetchFixture(FixtureId::fromRequest($request)))
            )
        );

        return (new PartialFixtureResource($resource))
            ->setFilterInputName('filter')
            ->setLeagueFilterInputName('league_filter')
            ->withLeagueResource(PartialLeagueResource::class);
    }
}
