<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\Services\FetchLeagueStandingService;
use Module\Football\Http\Requests\FetchLeagueStandingRequest;
use Module\Football\Http\Resources\PartialLeagueStandingResource;

final class FetchLeagueStandingController
{
    public function __invoke(FetchLeagueStandingRequest $request, FetchLeagueStandingService $service): PartialLeagueStandingResource
    {
        return (new PartialLeagueStandingResource($service->fromRequest($request)))
            ->setFilterInputName('fields')
            ->setLeagueFilterInputName('league_fields');
    }
}
