<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use App\ValueObjects\PositiveNumber;

/**
 * Time elapsed (in Minutes) in a fixture
 */
final class TimeElapsed
{
    public const START = 1;
    public const HALF_TIME = 45;
    public const END_FULL_TIME = 90;
    public const END_AFTER_PEN = 120;

    public function __construct(private int $elapsed)
    {
        PositiveNumber::check($elapsed);

        if ($elapsed > self::END_AFTER_PEN) {
            throw new \InvalidArgumentException('Time elapsed must not exceed ' . self::END_AFTER_PEN);
        }
    }

    public static function fromMinutes(int $elapsed): static
    {
        return new static($elapsed);
    }

    public function minutes(): int
    {
        return $this->elapsed;
    }
}
