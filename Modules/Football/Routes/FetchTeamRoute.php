<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\TeamId;

final class FetchTeamRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private TeamId $id)
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_TEAM, [
            'id'    => $this->id->toInt()
        ]);
    }
}
