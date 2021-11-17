<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\DTO\Coach;
use Module\Football\ValueObjects\CoachId;

interface FetchCoachRepositoryInterface
{
    /**
     * The full data of the coach is returned.
     * Only the team id, name and logo is returned for the coach's current team
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function byId(CoachId $id): Coach;
}
