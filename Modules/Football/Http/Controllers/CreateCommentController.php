<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Module\Football\Services\CreateCommentService;
use Module\Football\Http\Requests\CreateCommentRequest;

final class CreateCommentController
{
    public function __invoke(CreateCommentRequest $request, CreateCommentService $service): JsonResponse
    {
        $service->fromRequest($request);

        return response()->json(status: Response::HTTP_CREATED);
    }
}
