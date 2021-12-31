<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\PlayerTransferHistory;
use Module\Football\ValueObjects\PlayerId;

interface FetchPlayerTransferHistoryRepositoryInterface
{
    /**
     * Only the player name and id is returned.
     * only the team name, id and logo url is returned for each team (both team departed and team joined)
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function forPlayer(PlayerId $id): PlayerTransferHistory;
}
