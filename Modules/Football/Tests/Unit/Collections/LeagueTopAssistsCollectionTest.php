<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use LogicException;
use Tests\TestCase;
use Module\Football\Factories\PlayerFactory;
use Module\Football\ValueObjects\LeagueTopAssist;
use Module\Football\Collections\LeagueTopAssistsCollection;

class LeagueTopAssistsCollectionTest extends TestCase
{
    public function test_players_must_be_unique(): void
    {
        $this->expectException(LogicException::class);

        $player = PlayerFactory::new()->toDto();

        new LeagueTopAssistsCollection([
            new LeagueTopAssist($player, 22),
            new LeagueTopAssist(PlayerFactory::new()->toDto(), 21),
            new LeagueTopAssist(PlayerFactory::new()->toDto(), 20),
            new LeagueTopAssist($player, 19),
        ]);
    }
}
