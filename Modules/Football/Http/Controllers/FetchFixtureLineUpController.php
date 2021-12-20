<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureLineUpService;
use Module\Football\Http\Resources\FixtureLineUpResource;

final class FetchFixtureLineUpController
{
    public function __invoke(Request $request, FetchFixtureLineUpService $service): FixtureLineUpResource
    {
        $request->validate([
            'id' => ['required', new ResourceIdRule()]
        ]);

        return new FixtureLineUpResource($service->fetchLineUp(FixtureId::fromRequest($request)));
    }
}
