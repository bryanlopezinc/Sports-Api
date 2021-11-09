<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\DTO\Team;
use Module\Football\Collections\FixtureStatisticsCollection;

final class TeamFixtureStatistics
{
    public function __construct(private Team $team, private FixtureStatisticsCollection $stats)
    {

    }

    public function team(): Team
    {
        return $this->team;
    }

    public function statistics(): FixtureStatisticsCollection
    {
        return $this->stats;
    }
}
