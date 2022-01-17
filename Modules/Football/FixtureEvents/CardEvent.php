<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Player;
use Module\Football\DTO\Team;
use Module\Football\ValueObjects\TimeElapsed;

final class CardEvent implements EventInterface
{
    public const YELLOW_CARD    = 'yellow';
    public const SECOND_YELLOW  = 'second yellow';
    public const RED_CARD       = 'Red card';

    public function __construct(
        private string $type,
        private Player $player,
        private TeamEvent $teamEvent,
    ) {
        if (notInArray($type, [self::YELLOW_CARD, self::SECOND_YELLOW, self::RED_CARD])) {
            throw new \InvalidArgumentException();
        }
    }

    public function isSecondYellowCard(): bool
    {
        return $this->type === self::SECOND_YELLOW;
    }

    public function isYellowCard(): bool
    {
        return $this->type === self::YELLOW_CARD;
    }

    public function isRedCard(): bool
    {
        return $this->type === self::RED_CARD;
    }

    public function player(): Player
    {
        return $this->player;
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
