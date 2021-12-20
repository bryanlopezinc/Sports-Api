<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\Http\Requests\FetchFixtureStatisticsRequest;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureStatisticsService;
use Module\Football\Http\Resources\FixtureStatisticsResource;

final class FetchFixtureStatisticsController
{
    public function __invoke(FetchFixtureStatisticsRequest $request, FetchFixtureStatisticsService $service): FixtureStatisticsResource
    {
        return new FixtureStatisticsResource($service->fetch(FixtureId::fromRequest($request)));
    }
}
