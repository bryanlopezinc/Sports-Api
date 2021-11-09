<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Http\Resources\FixtureResource;
use Module\Football\Http\Requests\FetchFixtureRequest;

final class FetchFixtureController
{
    public function __invoke(FetchFixtureRequest $request, FetchFixtureService $service): FixtureResource
    {
        return new FixtureResource($service->fetchFixture(FixtureId::fromRequest($request)));
    }
}
