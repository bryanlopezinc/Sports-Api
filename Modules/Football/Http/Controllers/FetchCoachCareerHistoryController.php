<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\CoachId;
use Module\Football\Http\Resources\CoachCareerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Http\Requests\fetchCoachCareerHistoryRequest as Request;
use Module\Football\Contracts\Repositories\FetchCoachCareerHistoryRepositoryInterface;

final class FetchCoachCareerHistoryController
{
    public function __invoke(Request $request, FetchCoachCareerHistoryRepositoryInterface $repository): AnonymousResourceCollection
    {
        return CoachCareerResource::collection(
            $repository->byId(CoachId::fromRequest($request))->toLaravelCollection()
        );
    }
}
