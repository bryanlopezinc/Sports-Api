<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Collections\FixturesCollection;
use Module\Football\TeamsHeadToHeadTTL;

final class SetTeamsHeadToHeadMaxAgeMiddleware
{
    /**
     * @param  Request $request
     * @param  callable  $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, $next)
    {
        /** @var JsonResponse|Response $response */
        $response = $next($request);

        if (!$response->isSuccessful()) {
            return $response;
        }

        $fixtures = $response->getOriginalContent()->map(fn (JsonResource $jr) => $jr->resource)->pipeInto(FixturesCollection::class);

        $response->header('max-age', (new TeamsHeadToHeadTTL)($fixtures)->ttl()->second);

        return $response;
    }
}
