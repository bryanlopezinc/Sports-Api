<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TimeElapsed;

final class VarEvent implements EventInterface
{
    public const GOAL_CANCELLED     = 'Penalty Cancelled';
    public const PENALTY_AWARDED    = 'Penalty Awarded';

    public function __construct(private string $type, private TeamEvent $teamEvent)
    {
        if (notInArray($type, [self::GOAL_CANCELLED, self::PENALTY_AWARDED])) {
            throw new \InvalidArgumentException();
        }
    }

    public function isCancelledGoal(): bool
    {
        return $this->type === self::GOAL_CANCELLED;
    }

    public function penaltyAwarded(): bool
    {
        return $this->type === self::PENALTY_AWARDED;
    }

    public function type(): string
    {
        return $this->type;
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
