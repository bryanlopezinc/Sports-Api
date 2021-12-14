<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Http\Traits\ValidatesFixtureId;
use Module\Football\Services\FetchFixtureEventsService;
use Module\Football\Http\Resources\FixtureEventsResource;

final class FetchFixtureEventsController
{
    use ValidatesFixtureId;

    public function __invoke(Request $request, FetchFixtureEventsService $service): FixtureEventsResource
    {
        $request->validate([
            'id' => $this->rules()
        ]);

        return new FixtureEventsResource($service->fetch(FixtureId::fromRequest($request)));
    }
}
