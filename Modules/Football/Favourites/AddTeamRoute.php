<?php

declare(strict_types=1);

namespace Module\Football\Favourites;

use Module\Football\Routes\RouteName;
use Module\Football\ValueObjects\TeamId;

final class AddTeamRoute
{
    public function __construct(private TeamId $id)
    {
    }

    public function __toString()
    {
        return route(RouteName::ADD_TEAM_TO_FAVOURITES, [
            'id' => $this->id->asHashedId()
        ]);
    }

    public function toString(): string
    {
        return (string) $this;
    }
}
