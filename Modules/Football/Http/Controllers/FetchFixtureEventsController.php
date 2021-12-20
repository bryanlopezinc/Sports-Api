<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureEventsService;
use Module\Football\Http\Resources\FixtureEventsResource;

final class FetchFixtureEventsController
{
    public function __invoke(Request $request, FetchFixtureEventsService $service): FixtureEventsResource
    {
        $request->validate([
            'id' => ['required', new ResourceIdRule()]
        ]);

        return new FixtureEventsResource($service->fetch(FixtureId::fromRequest($request)));
    }
}
