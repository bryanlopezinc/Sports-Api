<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;
use App\ValueObjects\PositiveNumber;

final class MatchGoals
{
    public const MAX = 60;

    public function __construct(private int $goals)
    {
        $this->validate();
    }

    public function toInt(): int
    {
        return $this->goals;
    }

    private function validate(): void
    {
        PositiveNumber::check($this->goals);

        if ($this->goals > self::MAX) {
            throw new  LogicException('match goals should not be greater than ' . self::MAX);
        }
    }
}
