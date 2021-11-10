<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use LogicException;
use Tests\TestCase;
use Module\Football\Factories\PlayerFactory;
use Module\Football\ValueObjects\LeagueTopScorer;
use Module\Football\Collections\LeagueTopScorersCollection;

class LeagueTopScorersCollectionTest extends TestCase
{
    public function test_players_must_be_unique(): void
    {
        $this->expectException(LogicException::class);

        $player = PlayerFactory::new()->toDto();

        new LeagueTopScorersCollection([
            new LeagueTopScorer($player, 22),
            new LeagueTopScorer(PlayerFactory::new()->toDto(), 21),
            new LeagueTopScorer(PlayerFactory::new()->toDto(), 20),
            new LeagueTopScorer($player, 19),
        ]);
    }
}
