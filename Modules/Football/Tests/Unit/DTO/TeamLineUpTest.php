<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\DTO;

use LogicException;
use Module\Football\Collections\TeamMissingPlayersCollection;
use Tests\TestCase;
use Module\Football\Factories\PlayerFactory;
use Module\Football\DTO\Builders\TeamLineUpBuilder;
use Module\Football\TeamMissingPlayer;
use Module\Football\ValueObjects\ReasonForMissingFixture;

class TeamLineUpTest extends TestCase
{
    public function test_starting_eleven_cannot_exceeds_11(): void
    {
        $this->expectException(LogicException::class);

        (new TeamLineUpBuilder)
            ->setStartingEleven(PlayerFactory::new()->count(12)->toCollection())
            ->build();
    }

    public function test_missing_players_contains_unique_players(): void
    {
        $this->expectExceptionCode(1220);

        $missingPlayers = [
            new TeamMissingPlayer($player = PlayerFactory::new()->toDto(), new ReasonForMissingFixture(ReasonForMissingFixture::INJURED)),
            new TeamMissingPlayer(PlayerFactory::new()->toDto(), new ReasonForMissingFixture(ReasonForMissingFixture::INJURED)),
            new TeamMissingPlayer($player, new ReasonForMissingFixture(ReasonForMissingFixture::INJURED))
        ];

        (new TeamLineUpBuilder)
            ->setStartingEleven(PlayerFactory::new()->count(11)->toCollection())
            ->setMissingPlayers(new TeamMissingPlayersCollection($missingPlayers))
            ->build();
    }
}
