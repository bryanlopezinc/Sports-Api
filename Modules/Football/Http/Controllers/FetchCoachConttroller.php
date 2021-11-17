<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\CoachId;
use Module\Football\Services\FetchCoachService;
use Module\Football\Http\Resources\CoachResource;
use Module\Football\Http\Requests\FetchCoachRequest;

final class FetchCoachConttroller
{
    public function __invoke(FetchCoachRequest $request, FetchCoachService $service): CoachResource
    {
        return new CoachResource($service->findById(CoachId::fromRequest($request)));
    }
}
