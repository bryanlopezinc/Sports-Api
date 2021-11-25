<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit;

use Module\Football\Collections\TeamMissingPlayersCollection;
use Module\Football\DTO\Builders\TeamLineUpBuilder;
use Module\Football\Factories\CoachFactory;
use Module\Football\Factories\PlayerFactory;
use Module\Football\FixtureLineUp;
use Tests\TestCase;
use Module\Football\ValueObjects\TeamFormation;

class FixtureLineUpTest extends TestCase
{
    public function test_teams_cannot_have_same_coach(): void
    {
        $this->expectExceptionCode(3000);

        $homeTeamLineUp = (new TeamLineUpBuilder)
            ->setCoach($coach = CoachFactory::new()->toDto())
            ->setFormation(TeamFormation::fromString('4-4-2'))
            ->setStartingEleven(PlayerFactory::new()->count(11)->toCollection())
            ->setMissingPlayers(new TeamMissingPlayersCollection([]))
            ->build();

        $awayTeamLineUp = (new TeamLineUpBuilder)
            ->setCoach($coach)
            ->setFormation(TeamFormation::fromString('4-4-2'))
            ->setStartingEleven(PlayerFactory::new()->count(11)->toCollection())
            ->setMissingPlayers(new TeamMissingPlayersCollection([]))
            ->build();

        new FixtureLineUp($homeTeamLineUp, $awayTeamLineUp);
    }
}
