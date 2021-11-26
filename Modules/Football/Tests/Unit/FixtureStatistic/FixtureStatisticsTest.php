<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\FixtureStatistic;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\DTO\Builders\FixtureStatisticsBuilder;

class FixtureStatisticsTest extends TestCase
{
    public function test_statistics_must_contain_two_different_teams(): void
    {
        $this->expectExceptionCode(422);

        $stats = (new FixtureStatisticsBuilder())->team(TeamFactory::new()->toDto())->build();

        new FixtureStatistics(new FixtureId(2), $stats, $stats);
    }

    public function test_valid_ball_possession_spread(): void
    {
        $this->expectExceptionCode(426);

        $statsTeam1 = (new FixtureStatisticsBuilder())->possession(50)->team(TeamFactory::new()->toDto())->build();
        $statsTeam2 = (new FixtureStatisticsBuilder())->possession(60)->team(TeamFactory::new()->toDto())->build();

        new FixtureStatistics(new FixtureId(2), $statsTeam1, $statsTeam2);
    }
}
