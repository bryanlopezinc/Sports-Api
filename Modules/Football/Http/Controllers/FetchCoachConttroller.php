<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Http\Request;
use Module\Football\Contracts\Repositories\FetchCoachRepositoryInterface;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Http\Resources\CoachResource;

final class FetchCoachConttroller
{
    public function __invoke(Request $request, FetchCoachRepositoryInterface $repository): CoachResource
    {
        $request->validate([
            'id' => ['required', new ResourceIdRule()],
        ]);

        return new CoachResource($repository->byId(CoachId::fromRequest($request)));
    }
}
