<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use App\Utils\Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Module\Football\DTO\Fixture;
use Illuminate\Http\JsonResponse;

final class SetFindFixtureResponseHeadersMiddleware
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

        if (!is_null($maxAge = $this->caclculateMaxAgeFrom($response->getOriginalContent()))) {
            $response->header('max-age', (string)$maxAge);
        }

        return $response;
    }

    private function caclculateMaxAgeFrom(Fixture $fixture): ?int
    {
        if ($fixture->status()->isFinished()) {
            return Config::get('football.finishedFixtureMaxAge');
        }

        if ($fixture->status()->isInProgress()) {
            return Config::get('football.FixtureInProgressMaxAge');
        }

        return null;
    }
}
