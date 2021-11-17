<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\CoachId;
use Module\Football\Collections\CoachCareerHistory;

interface FetchCoachCareerHistoryRepositoryInterface
{
    /**
     * Only the team id, name and logo is returned for each teams in the coachCareer
     * In cases where a coach managed a team that has no reference in the data provider only the team name is returned.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function byId(CoachId $id): CoachCareerHistory;
}
