<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;

final class CoachAge
{
    public const MIN_AGE = 17;
    public const MAX_AGE = 100;

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
        if ($this->age < self::MIN_AGE) {
            throw new LogicException('Coach age should not be less than ' . self::MIN_AGE, 433);
        }

        if ($this->age > self::MAX_AGE) {
            throw new LogicException('Coach age should not be greater than ' . self::MAX_AGE, 434);
        }
    }
}
