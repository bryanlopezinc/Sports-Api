<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\ResourceIdRule;
use App\ValueObjects\ResourceId;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Module\Football\Services\DeleteCommentService;

final class DeleteCommentController
{
    public function __invoke(Request $request, DeleteCommentService $service): JsonResponse
    {
        $request->validate(['id' => ['required', new ResourceIdRule]]);

        $service(new ResourceId((int) $request->input('id')));

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
