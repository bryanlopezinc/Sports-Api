<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\FixtureLineUp;
use Module\Football\ValueObjects\FixtureId;

interface FetchFixtureLineUpRepositoryInterface
{
    /**
     * Only team attributes - id, name, logo are returned for each team in line up.
     * Only id, name and photoUrl attributes are returned for each coach.
     * Player name, id, jerseyNumber, position and position on grid are returned for each player.
     * only Id, name and photo is returned for each missing player.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException;
     */
    public function fetchLineUp(FixtureId $id): FixtureLineUp;
}
