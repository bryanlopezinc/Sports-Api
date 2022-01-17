<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\LeagueId;

final class FetchLeagueRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private LeagueId $id, private array $query = [])
    {
    }

    public function __toString()
    {
        return route(RouteName::FIND_LEAGUE, array_merge([
            'id' => $this->id->asHashedId()
        ], $this->query));
    }
}
