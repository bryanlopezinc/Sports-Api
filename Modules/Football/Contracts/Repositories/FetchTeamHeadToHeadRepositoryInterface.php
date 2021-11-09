<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\TeamsHeadToHead;

interface FetchTeamHeadToHeadRepositoryInterface
{
    /**
     * only the league id, name, country, logo and season (year) are returned for the fixture league.
     * only venue name and city attributes are returned for the fixture venue.
     * Only id, name, logo are returned for each team in the fixture.
     * The full fixture data are returned in the response.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function getHeadToHead(TeamId $teamOne, TeamId $teamTwo): TeamsHeadToHead;
}
