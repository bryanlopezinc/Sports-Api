<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTable;

interface FetchLeagueStandingRepositoryInterface
{
    /**
     * only the team id, name, logo are returned for teams in each standing.
     * The full league data of the league table is returned in the response
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function getLeagueTable(LeagueId $leagueId, Season $season): LeagueTable;
}
