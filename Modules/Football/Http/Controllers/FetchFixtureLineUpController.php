<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Http\Requests\FetchFixtureLineUpRequest;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureLineUpService;
use Module\Football\Http\Resources\FixtureLineUpResource;

final class FetchFixtureLineUpController
{
    public function __invoke(FetchFixtureLineUpRequest $request, FetchFixtureLineUpService $service): FixtureLineUpResource|JsonResource
    {
        $fixtureLineUp = $service->fetchLineUp(FixtureId::fromRequest($request));

        if ($fixtureLineUp->isEmpty()) {
            return new JsonResource([]);
        }

        return new FixtureLineUpResource($fixtureLineUp);
    }
}
