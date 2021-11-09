<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Player;

final class MissedPenaltyEvent implements TeamEventInterface
{
    use HasTeamEvent;

    public function __construct(private Player $penaltyTaker, private TeamEvent $teamEvent)
    {
    }

    public function missedBy(): Player
    {
        return $this->penaltyTaker;
    }
}
