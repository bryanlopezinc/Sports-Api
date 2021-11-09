<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\FixtureEvents;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\PlayerFactory;
use Module\Football\FixtureEvents\GoalEvent;
use Module\Football\FixtureEvents\TeamEvent;
use Module\Football\ValueObjects\TimeElapsed;

class GoalEventTest extends TestCase
{
    public function test_player_cannot_assists_self(): void
    {
        $this->expectExceptionCode(423);

        $player = PlayerFactory::new()->toDto();

        new GoalEvent(
            $player,
            GoalEvent::NORMAL_GOAL,
            $player,
            $this->getTeamEvent()
        );
    }

    public function test_penalty_goal_cannot_have_assist(): void
    {
        $this->expectExceptionCode(422);

        new GoalEvent(
            PlayerFactory::new()->toDto(),
            GoalEvent::PENALTY,
            PlayerFactory::new()->toDto(),
            $this->getTeamEvent()
        );
    }

    public function test_own_goal_cannot_have_assist(): void
    {
        $this->expectExceptionCode(422);

        new GoalEvent(
            PlayerFactory::new()->toDto(),
            GoalEvent::OWN_GOAL,
            PlayerFactory::new()->toDto(),
            $this->getTeamEvent()
        );
    }

    private function getTeamEvent(): TeamEvent
    {
        $team = TeamFactory::new()->toDto();

        return new TeamEvent($team, TimeElapsed::fromMinutes(30));
    }
}
