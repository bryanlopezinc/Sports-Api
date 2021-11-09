<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureLineUpService;
use Module\Football\Http\Resources\FixtureLineUpResource;
use Module\Football\Http\Requests\FetchFixtureLineUpRequest;

final class FetchFixtureLineUpController
{
    public function __invoke(FetchFixtureLineUpRequest $request, FetchFixtureLineUpService $service): FixtureLineUpResource
    {
        return new FixtureLineUpResource($service->fetchLineUp(FixtureId::fromRequest($request)));
    }
}
