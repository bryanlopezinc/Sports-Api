<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;

final class FetchLeagueTopScorersRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private LeagueId $id, private Season $season)
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_LEAGUE_TOP_SCORERS, [
            'id'     => $this->id->asHashedId(),
            'season' => $this->season->toInt()
        ]);
    }
}
