<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Collections\TeamIdsCollection;

interface FetchTeamRepositoryInterface
{
    /**
     * The full data of the team is returned.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findTeamById(TeamId $id): Team;

    /**
     * The full data of each team is returned.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findManyById(TeamIdsCollection $ids): TeamsCollection;
}
