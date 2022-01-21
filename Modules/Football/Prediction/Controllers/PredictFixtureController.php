<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Controllers;

use Illuminate\Http\JsonResponse;
use Module\Football\Prediction\PredictFixtureRequest;
use Module\Football\Prediction\Services\CreateUserPredictionService;
use Symfony\Component\HttpFoundation\Response;

final class PredictFixtureController
{
    public function __invoke(PredictFixtureRequest $request, CreateUserPredictionService $service): JsonResponse
    {
        $service->FromRequest($request);

        return response()->json(['success'], Response::HTTP_CREATED);
    }
}
