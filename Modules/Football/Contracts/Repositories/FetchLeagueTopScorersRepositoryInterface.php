<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeagueTopScorersCollection;

interface FetchLeagueTopScorersRepositoryInterface
{
    /**
     * only the player name, id, country, photo url and height is returned for each player
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function topScorerers(LeagueId $leagueId, Season $season): LeagueTopScorersCollection;
}