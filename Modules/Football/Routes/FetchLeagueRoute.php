<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\LeagueId;

final class FetchLeagueRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private LeagueId $id)
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_LEAGUE, [
            'id'    => $this->id->toInt()
        ]);
    }
}
