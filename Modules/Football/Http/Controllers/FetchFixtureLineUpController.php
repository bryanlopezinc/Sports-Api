<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Http\Traits\ValidatesFixtureId;
use Module\Football\Services\FetchFixtureLineUpService;
use Module\Football\Http\Resources\FixtureLineUpResource;

final class FetchFixtureLineUpController
{
    use ValidatesFixtureId;

    public function __invoke(Request $request, FetchFixtureLineUpService $service): FixtureLineUpResource
    {
        $request->validate([
            'id' => $this->rules()
        ]);

        return new FixtureLineUpResource($service->fetchLineUp(FixtureId::fromRequest($request)));
    }
}
