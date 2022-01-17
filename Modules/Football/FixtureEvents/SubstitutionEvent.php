<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use LogicException;
use Module\Football\DTO\Player;
use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TimeElapsed;

final class SubstitutionEvent implements EventInterface
{
    public function __construct(
        private Player $playerIn,
        private Player $playerOut,
        private TeamEvent $teamEvent,
    ) {
        $this->ensurePlayerDidNotSubstituteSelf();
    }

    private function ensurePlayerDidNotSubstituteSelf(): void
    {
        $playerSubstitutedSelf = $this->playerIn()->getId()->equals($this->playerOut()->getId());

        if ($playerSubstitutedSelf) {
            throw new LogicException('Player cannot substitute self');
        }
    }

    public function playerIn(): Player
    {
        return $this->playerIn;
    }

    public function playerOut(): Player
    {
        return $this->playerOut;
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
