<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use LogicException;
use Tests\TestCase;
use InvalidArgumentException;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Factories\FixtureFactory;
use Module\Football\Factories\TeamFactory;
use Module\Football\ValueObjects\TeamsHeadToHead;

class TeamsHeadToHeadTest extends TestCase
{
    public function test_throws_exception_when_team_ids_are_same(): void
    {
        $this->expectException(LogicException::class);

        $fixtures = FixtureFactory::new()
            ->count(3)
            ->homeTeam($team = TeamFactory::new()->toDto())
            ->awayTeam($team)
            ->winner($team)
            ->toCollection();

        $ids = $fixtures->teams()->pluckIds()->toLaravelCollection();

        new TeamsHeadToHead($ids->first(), $ids->first(), $fixtures);
    }

    public function test_throws_exception_when_all_fixtures_teams_are_not_same_with_head_to_head_teams(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $fixtures = FixtureFactory::new()->count(2)->toCollection();

        $ids = $fixtures
            ->teams()
            ->pluckIds()
            ->toLaravelCollection()
            ->map(fn (TeamId $id) => new TeamId($id->toInt() + 1));

        new TeamsHeadToHead($ids->first(), $ids->last(), $fixtures);
    }

    public function test_throws_exception_when_one_fixture_team_is_not_same_with_head_to_head_teams(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $fixtures = FixtureFactory::new()->count(2)->toCollection();

        $ids = $fixtures
            ->teams()
            ->pluckIds()
            ->toLaravelCollection()
            ->map(function (TeamId $teamId, int $index) {
                return $index === 1 ? new TeamId($teamId->toInt() + 1) : $teamId;
            });

        new TeamsHeadToHead($ids->first(), $ids->last(), $fixtures);
    }
}
