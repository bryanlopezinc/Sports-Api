<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use Illuminate\Http\Request;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\FetchLeagueService;
use Module\Football\Exceptions\Http\CoverageNotSupportedHttpException;

final class CheckCoversLeagueTopAssistsMiddleware
{
    public function __construct(private FetchLeagueService $service)
    {
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $next)
    {
        $league = $this->service->findByIdAndSeason(LeagueId::fromRequest($request), Season::fromString($request->input('season')));

        if (!$league->getSeason()->getCoverage()->coversTopAssists()) {
            throw new CoverageNotSupportedHttpException('TopAssistsNotSupported');
        }

        return $next($request);
    }
}
