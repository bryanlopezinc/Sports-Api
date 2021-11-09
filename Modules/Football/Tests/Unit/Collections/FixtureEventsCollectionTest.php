<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use LogicException;
use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\FixtureEvents\VarEvent;
use Module\Football\Factories\PlayerFactory;
use Module\Football\FixtureEvents\CardEvent;
use Module\Football\FixtureEvents\TeamEvent;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\FixtureEvents\SubstitutionEvent;
use Module\Football\Collections\FixtureEventsCollection;

class FixtureEventsCollectionTest extends TestCase
{
    public function test_must_not_contain_more_than_two_teams(): void
    {
        $this->expectException(LogicException::class);

        new FixtureEventsCollection([
            new VarEvent(VarEvent::GOAL_CANCELLED, new TeamEvent(TeamFactory::new()->toDto(), TimeElapsed::fromMinutes(8))),
            new VarEvent(VarEvent::GOAL_CANCELLED, new TeamEvent(TeamFactory::new()->toDto(), TimeElapsed::fromMinutes(10))),
            new VarEvent(VarEvent::GOAL_CANCELLED, new TeamEvent(TeamFactory::new()->toDto(), TimeElapsed::fromMinutes(13)))
        ]);
    }

    public function test_players_can_only_be_subbed_off_once(): void
    {
        $this->expectExceptionCode(422);

        $eventInfo = new TeamEvent(TeamFactory::new()->toDto(), TimeElapsed::fromMinutes(10));
        $playerOff = PlayerFactory::new()->toDto();

        new FixtureEventsCollection([
            new SubstitutionEvent(PlayerFactory::new()->toDto(), $playerOff, $eventInfo),
            new SubstitutionEvent(PlayerFactory::new()->toDto(), $playerOff, $eventInfo),
        ]);
    }

    public function test_player_can_only_be_subbed_on_once(): void
    {
        $this->expectExceptionCode(423);

        $eventInfo = new TeamEvent(TeamFactory::new()->toDto(), TimeElapsed::fromMinutes(10));
        $playerIn = PlayerFactory::new()->toDto();

        new FixtureEventsCollection([
            new SubstitutionEvent($playerIn, PlayerFactory::new()->toDto(), $eventInfo),
            new SubstitutionEvent($playerIn, PlayerFactory::new()->toDto(), $eventInfo),
        ]);
    }

    public function test_player_cannot_receieve_more_than_one_red_card(): void
    {
        $this->expectException(LogicException::class);

        $eventInfo = new TeamEvent(TeamFactory::new()->toDto(), TimeElapsed::fromMinutes(10));
        $player = PlayerFactory::new()->toDto();

       new FixtureEventsCollection([
            new CardEvent(CardEvent::RED_CARD, $player, $eventInfo),
            new CardEvent(CardEvent::RED_CARD, $player, $eventInfo),
        ]);
    }
}
