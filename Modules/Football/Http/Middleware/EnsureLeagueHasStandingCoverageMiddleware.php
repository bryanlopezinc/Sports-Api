<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use App\Exceptions\Http\HttpException;
use Illuminate\Http\Request;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\FetchLeagueService;

final class EnsureLeagueHasStandingCoverageMiddleware
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
        $league = $this->service->findByIdAndSeason(LeagueId::fromRequest($request, 'league_id'), Season::fromString($request->input('season')));

        if (!$league->getSeason()->getCoverage()->coversLeagueStanding()) {
            throw new HttpException(403, 'League standing not supported for league season');
        }

        return $next($request);
    }
}
