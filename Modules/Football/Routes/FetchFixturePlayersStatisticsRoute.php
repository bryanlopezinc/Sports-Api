<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\FixtureId;

final class FetchFixturePlayersStatisticsRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private FixtureId $id)
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_FIXTURES_PLAYERS_STAT, [
            'id'    => $this->id->toInt()
        ]);
    }
}
