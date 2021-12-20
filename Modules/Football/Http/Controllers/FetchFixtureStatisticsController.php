<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureStatisticsService;
use Module\Football\Http\Resources\FixtureStatisticsResource;

final class FetchFixtureStatisticsController
{
    public function __invoke(Request $request, FetchFixtureStatisticsService $service): FixtureStatisticsResource
    {
        $request->validate([
            'id' => ['required', new ResourceIdRule()]
        ]);

        return new FixtureStatisticsResource($service->fetch(FixtureId::fromRequest($request)));
    }
}
