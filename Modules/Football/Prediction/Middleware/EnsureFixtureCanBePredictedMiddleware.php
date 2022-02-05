<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Module\Football\Prediction\PredictFixtureRequest;
use Module\Football\ValueObjects\FixtureId;
use Symfony\Component\HttpFoundation\Response;
use Module\Football\Services\FetchFixtureService;

final class EnsureFixtureCanBePredictedMiddleware
{
    public function __construct(private FetchFixtureService $service)
    {
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $next)
    {
        //Ensure all attributes needed for validation are present and valid.
        app(PredictFixtureRequest::class);

        $status = $this->service->fetchFixture(FixtureId::fromRequest($request, 'fixture_id'))->status();

        if ($status->isNotStarted()) {
            return $next($request);
        }

        throw new HttpException(Response::HTTP_FORBIDDEN, 'Can only predict a fixture that is not started');
    }
}
