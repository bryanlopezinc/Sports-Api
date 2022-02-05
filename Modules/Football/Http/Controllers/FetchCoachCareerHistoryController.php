<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Http\Request;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Http\Resources\CoachCareerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Contracts\Repositories\FetchCoachCareerHistoryRepositoryInterface;

final class FetchCoachCareerHistoryController
{
    public function __invoke(Request $request, FetchCoachCareerHistoryRepositoryInterface $repository): AnonymousResourceCollection
    {
        $request->validate([
            'id' => ['required', new ResourceIdRule()],
        ]);

        return CoachCareerResource::collection(
            $repository->byId(CoachId::fromRequest($request))->toLaravelCollection()
        );
    }
}
