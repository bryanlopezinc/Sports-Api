<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;

final class PlayerAge
{
    public const MIN = 10;
    public const MAX = 75;

    public function __construct(private int $age)
    {
        $this->validate();
    }

    public static function fromInt(int $age): static
    {
        return new static($age);
    }

    public function toInt(): int
    {
        return $this->age;
    }

    protected function validate(): void
    {
        if ($this->age < self::MIN) {
            throw new LogicException('player age should not be less than ' . self::MIN, 400);
        }

        if ($this->age > self::MAX) {
            throw new LogicException('player age should not be greater than ' . self::MAX, 401);
        }
    }
}
