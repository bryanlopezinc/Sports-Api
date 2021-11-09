<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureStatistic\FixtureStatistics;

interface FetchFixtureStatisticsRepositoryInterface
{
    /**
     * only the team id, name, logo are returned for teams
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException;
     * @throws \Module\Football\Exceptions\Http\FixtureStatisticsNotAvailableHttpException
     */
    public function fetchStats(FixtureId $id): FixtureStatistics;
}
