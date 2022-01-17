<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Player;
use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TimeElapsed;

final class MissedPenaltyEvent implements EventInterface
{
    public function __construct(private Player $penaltyTaker, private TeamEvent $teamEvent)
    {
    }

    public function missedBy(): Player
    {
        return $this->penaltyTaker;
    }

    public function team(): Team
    {
        return $this->teamEvent->getTeam();
    }

    public function time(): TimeElapsed
    {
        return $this->teamEvent->time();
    }
}
