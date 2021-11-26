<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

interface FetchPlayersStatisticsRepositoryInterface
{
    /**
     * Each team in the playersStatistic dto contains only the team id, name and logo
     * and each player contains only player id, name, photoUrl and player postion.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function fetchStatistics(FixtureId $id): FixturePlayersStatisticsCollection;
}
