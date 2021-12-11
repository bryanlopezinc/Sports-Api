<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PredictFixtureController
{
    public function __invoke(PredictFixtureRequest $request, CreateUserPrediction $service): JsonResponse
    {
        $service->FromRequest($request);

        return response()->json(['success'], Response::HTTP_CREATED);
    }
}
