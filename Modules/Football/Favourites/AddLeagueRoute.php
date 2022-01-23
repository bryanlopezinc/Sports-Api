<?php

declare(strict_types=1);

namespace Module\Football\Favourites;

use Module\Football\Routes\RouteName;
use Module\Football\ValueObjects\LeagueId;

final class AddLeagueRoute
{
    public function __construct(private LeagueId $id)
    {
    }

    public function __toString()
    {
        return route(RouteName::ADD_LEAGUE_TO_FAVOURITES, [
            'id' => $this->id->asHashedId()
        ]);
    }

    public function toString(): string
    {
        return (string) $this;
    }
}
