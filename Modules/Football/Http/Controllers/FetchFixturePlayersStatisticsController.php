<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\FixtureId;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Http\Requests\FetchFixturePlayersStatisticsRequest;
use Module\Football\Services\FetchFixturePlayersStatisticsService;
use Module\Football\Http\Resources\FixturePlayerStatisticsResource;

final class FetchFixturePlayersStatisticsController
{
    public function __invoke(FetchFixturePlayersStatisticsRequest $request, FetchFixturePlayersStatisticsService $service): AnonymousResourceCollection
    {
        return FixturePlayerStatisticsResource::collection($service->fetch(FixtureId::fromRequest($request))->toLaravelCollection());
    }
}
