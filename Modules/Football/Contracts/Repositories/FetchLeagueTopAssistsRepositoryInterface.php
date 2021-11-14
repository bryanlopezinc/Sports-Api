<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTopAssistsCollection;

interface FetchLeagueTopAssistsRepositoryInterface
{
    /**
     * only the player name, id, country, photo url and height is returned for each player
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     * @throws \Module\Football\Exceptions\Http\LeagueTopAssistsNotAvailableHttpException
     */
    public function topAssists(LeagueId $leagueId, Season $season): LeagueTopAssistsCollection;
}
