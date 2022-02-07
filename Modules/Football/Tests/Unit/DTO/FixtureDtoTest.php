<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\DTO;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\FixtureFactory;

class FixtureDtoTest extends TestCase
{
    public function test_home_and_away_team_cannot_be_same(): void
    {
        $this->expectExceptionCode(600);

        $team = TeamFactory::new()->toDto();

        FixtureFactory::new()->homeTeam($team)->awayTeam($team)->toDto();
    }

    public function test_throws_exception_when_winner_id_does_not_belong_to_fixture_teams(): void
    {
        $this->expectExceptionCode(601);

        FixtureFactory::new()->winner(TeamFactory::new()->toDto())->toDto();
    }
}
