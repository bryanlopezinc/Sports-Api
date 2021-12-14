<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Http\Traits\ValidatesFixtureId;
use Module\Football\Services\FetchFixtureStatisticsService;
use Module\Football\Http\Resources\FixtureStatisticsResource;

final class FetchFixtureStatisticsController
{
    use ValidatesFixtureId;

    public function __invoke(Request $request, FetchFixtureStatisticsService $service): FixtureStatisticsResource
    {
        $request->validate([
            'id' => $this->rules()
        ]);

        return new FixtureStatisticsResource($service->fetch(FixtureId::fromRequest($request)));
    }
}
