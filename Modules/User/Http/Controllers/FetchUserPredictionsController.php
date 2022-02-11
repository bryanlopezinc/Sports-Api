<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use App\Http\Resources\PaginatedResourceCollection;
use Module\User\Services\FetchUserPredictionsService;
use Module\User\Http\Resources\UserPredictionResource;
use Module\User\Http\Requests\FetchUserPredictionsRequest;

final class FetchUserPredictionsController
{
    public function __construct(private FetchUserPredictionsService $service)
    {
    }

    public function guest(FetchUserPredictionsRequest $request): PaginatedResourceCollection
    {
        return new PaginatedResourceCollection($this->service->forGuestUser($request), UserPredictionResource::class);
    }

    public function auth(FetchUserPredictionsRequest $request): PaginatedResourceCollection
    {
        return new PaginatedResourceCollection($this->service->forAuthUser($request), UserPredictionResource::class);
    }
}
