<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Http\Requests\FetchFixtureRequest;
use Module\Football\Http\Resources\PartialLeagueResource;
use Module\Football\Http\Resources\PartialFixtureResource;

final class FetchFixtureController
{
    public function __invoke(FetchFixtureRequest $request, FetchFixtureService $service): PartialFixtureResource
    {
        return (new PartialFixtureResource($service->fetchFixture(FixtureId::fromRequest($request))))
            ->setFilterInputName('filter')
            ->setLeagueFilterInputName('league_filter')
            ->withLeagueResource(PartialLeagueResource::class);
    }
}
