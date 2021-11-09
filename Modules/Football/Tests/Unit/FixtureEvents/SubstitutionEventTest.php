<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\FixtureEvents;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\PlayerFactory;
use Module\Football\FixtureEvents\TeamEvent;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\FixtureEvents\SubstitutionEvent;

class SubstitutionEventTest extends TestCase
{
    public function test_player_cannot_substitute_self(): void
    {
        $this->expectException(\LogicException::class);

        $player = PlayerFactory::new()->toDto();
        $team = TeamFactory::new()->toDto();

        new SubstitutionEvent(
            $player,
            $player,
            new TeamEvent($team, TimeElapsed::fromMinutes(30))
        );
    }
}
