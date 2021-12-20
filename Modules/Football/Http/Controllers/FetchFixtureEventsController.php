<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\Http\Requests\FetchFixtureEventsRequest;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureEventsService;
use Module\Football\Http\Resources\FixtureEventsResource;

final class FetchFixtureEventsController
{
    public function __invoke(FetchFixtureEventsRequest $request, FetchFixtureEventsService $service): FixtureEventsResource
    {
        return new FixtureEventsResource($service->fetch(FixtureId::fromRequest($request)));
    }
}
