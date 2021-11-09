<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use InvalidArgumentException;

final class PlayerPosition
{
    public const ATTACKER   = 1;
    public const DEFENDER   = 2;
    public const GOALIE     = 3;
    public const MIDFIELDER = 4;

    public function __construct(private int $postion)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (notInArray($this->postion, [self::ATTACKER, self::DEFENDER, self::GOALIE, self::MIDFIELDER])) {
            throw new InvalidArgumentException(
                'invalid player postion code ' . $this->postion
            );
        }
    }

    public function isAttacker(): bool
    {
        return $this->postion === self::ATTACKER;
    }

    public function isMiddlFielder(): bool
    {
        return $this->postion === self::MIDFIELDER;
    }

    public function isDefender(): bool
    {
        return $this->postion === self::DEFENDER;
    }

    public function isGoalKeeper(): bool
    {
        return $this->postion === self::GOALIE;
    }
}
