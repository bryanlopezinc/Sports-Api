<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\Contracts\Repositories\FetchCoachRepositoryInterface;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Http\Resources\CoachResource;
use Module\Football\Http\Requests\FetchCoachRequest;

final class FetchCoachConttroller
{
    public function __invoke(FetchCoachRequest $request, FetchCoachRepositoryInterface $repository): CoachResource
    {
        return new CoachResource($repository->byId(CoachId::fromRequest($request)));
    }
}
