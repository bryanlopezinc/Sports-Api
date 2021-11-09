<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use App\ValueObjects\Date;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\FixturesCollection;

interface FetchLeagueFixturesByDateRepositoryInterface
{
    /**
     * The full fixture info is returned for each fixture in the collection.
     * Only id, name, logo are returned for each team in the fixture.
     * Only the league id,name,country,logo and season(year) are returned for each fixture league.
     */
    public function findBy(LeagueId $leagueId, Season $season, Date $date): FixturesCollection;
}
