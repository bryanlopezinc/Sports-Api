<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\FixtureStatistic;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\TeamFixtureStatistics;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Collections\FixtureStatisticsCollection;
use Module\Football\FixtureStatistic\BallPossesion;
use Module\Football\FixtureStatistic\GenericFixtureStatistic;

class FixtureStatisticsTest extends TestCase
{
    public function test_teams_must_have_same_statistics_type(): void
    {
        $this->expectExceptionCode(425);

        $teams = TeamFactory::new()->count(2)->toCollection();

        $statsTeam1 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->first(),
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5)
            ])
        );

        $statsTeam2 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->last(),
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::GOALKEPPER_SAVES, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5)
            ])
        );

        new FixtureStatistics(new FixtureId(2), $statsTeam1, $statsTeam2);
    }

    public function test_statistics_must_contain_two_different_teams(): void
    {
        $this->expectExceptionCode(422);

        $team = TeamFactory::new()->toDto();

        $statsTeam1 = new TeamFixtureStatistics(
            $team,
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5)
            ])
        );

        $statsTeam2 = new TeamFixtureStatistics(
            $team,
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5)
            ])
        );

        new FixtureStatistics(new FixtureId(2), $statsTeam1, $statsTeam2);
    }

    public function test_teams_must_have_same_amount_of_stats(): void
    {
        $this->expectExceptionCode(423);

        $teams = TeamFactory::new()->count(2)->toCollection();

        $statsTeam1 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->first(),
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5)
            ])
        );

        $statsTeam2 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->last(),
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::OFFSIDES, 5)
            ])
        );

        new FixtureStatistics(new FixtureId(2), $statsTeam1, $statsTeam2);
    }

    public function test_statistics_cannot_contain_duplicates(): void
    {
        $this->expectExceptionCode(424);

        $teams = TeamFactory::new()->count(2)->toCollection();

        $statsTeam1 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->first(),
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5),
            ])
        );

        $statsTeam2 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->last(),
            new FixtureStatisticsCollection([
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::CORNER_KICKS, 5),
                new GenericFixtureStatistic(GenericFixtureStatistic::BLOCKED_SHOTS, 5),
            ])
        );

        new FixtureStatistics(new FixtureId(2), $statsTeam1, $statsTeam2);
    }

    public function test_valid_ball_possession_spread(): void
    {
        $this->expectExceptionCode(426);

        $teams = TeamFactory::new()->count(2)->toCollection();

        $statsTeam1 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->first(),
            new FixtureStatisticsCollection([new BallPossesion(50)])
        );

        $statsTeam2 = new TeamFixtureStatistics(
            $teams->toLaravelCollection()->last(),
            new FixtureStatisticsCollection([new BallPossesion(60)])
        );

        new FixtureStatistics(new FixtureId(2), $statsTeam1, $statsTeam2);
    }
}
