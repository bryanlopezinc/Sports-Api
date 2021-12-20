<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Exceptions\Http\CoverageNotSupportedHttpException;
use Module\Football\Http\Requests\FetchFixtureEventsRequest;

final class CheckCoversEventsMiddleware
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
        app(FetchFixtureEventsRequest::class);

        $fixture = $this->service->fetchFixture(FixtureId::fromRequest($request));

        $coversEvents = $fixture->league()->getSeason()->getCoverage()->coversEvents();

        if (!$coversEvents) {
            throw new CoverageNotSupportedHttpException('FixtureEventsNotSupported');
        }

        return $next($request);
    }
}
