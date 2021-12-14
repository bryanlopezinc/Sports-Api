<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Http\Traits\ValidatesFixtureId;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Services\FetchFixturePlayersStatisticsService;
use Module\Football\Http\Resources\FixturePlayerStatisticsResource;

final class FetchFixturePlayersStatisticsController
{
    use ValidatesFixtureId;

    public function __invoke(Request $request, FetchFixturePlayersStatisticsService $service): AnonymousResourceCollection
    {
        $request->validate([
            'id' => $this->rules()
        ]);

        return FixturePlayerStatisticsResource::collection($service->fetch(FixtureId::fromRequest($request))->toLaravelCollection());
    }
}
