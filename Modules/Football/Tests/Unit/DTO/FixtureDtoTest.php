<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\DTO;

use LogicException;
use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\FixtureFactory;

class FixtureDtoTest extends TestCase
{
    public function test_home_and_away_team_cannot_be_same(): void
    {
        $this->expectException(LogicException::class);

        $team = TeamFactory::new()->toDto();

        FixtureFactory::new()->homeTeam($team)->awayTeam($team)->toDto();
    }

    public function test_throws_exception_when_winner_id_does_not_belong_to_fixture_teams(): void
    {
        $this->expectException(LogicException::class);

        $team = TeamFactory::new()->toDto();

        FixtureFactory::new()
            ->homeTeam($team)
            ->awayTeam($team)
            ->winnerId(TeamFactory::new()->toDto()->getId())
            ->toDto();
    }
}
