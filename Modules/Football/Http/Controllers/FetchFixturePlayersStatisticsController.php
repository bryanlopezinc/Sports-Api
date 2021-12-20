<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Services\FetchFixturePlayersStatisticsService;
use Module\Football\Http\Resources\FixturePlayerStatisticsResource;

final class FetchFixturePlayersStatisticsController
{
    public function __invoke(Request $request, FetchFixturePlayersStatisticsService $service): AnonymousResourceCollection
    {
        $request->validate([
            'id' => ['required', new ResourceIdRule()]
        ]);

        return FixturePlayerStatisticsResource::collection($service->fetch(FixtureId::fromRequest($request))->toLaravelCollection());
    }
}
