<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\PlayersCollection;

interface FetchTeamSquadRepositoryInterface
{
    /**
     * Only id, name, age, shirtNumber, position, and photo url
     *  are returned for each player in the collection.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function teamSquad(TeamId $teamId): PlayersCollection;
}
