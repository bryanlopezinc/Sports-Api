<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\CoachId;
use Module\Football\Http\Resources\CoachCareerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Services\FetchCoachCareerHistoryService as Service;
use Module\Football\Http\Requests\fetchCoachCareerHistoryRequest as Request;

final class FetchCoachCareerHistoryController
{
    public function __invoke(Request $request, Service $service): AnonymousResourceCollection
    {
        return CoachCareerResource::collection(
            $service->findById(CoachId::fromRequest($request))->toLaravelCollection()
        );
    }
}
