<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Exceptions\Http\CoverageNotSupportedHttpException;

final class CheckCoversFixtureLineUpMiddleware
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
        $fixture = $this->service->fetchFixture(FixtureId::fromRequest($request));

        $coversLineUps = $fixture->league()->getSeason()->getCoverage()->coverslineUp();

        if (!$coversLineUps) {
            throw new CoverageNotSupportedHttpException('FixtureLineUpNotSupported');
        }

        return $next($request);
    }
}
