<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class PositiveNumber
{
    public function __construct(private int $number)
    {
        if ($number < 0) {
            throw new \InvalidArgumentException('Number cannot be less than 0');
        }
    }

    /**
     * Throw an exception if number is negative
     *
     * @param int|array<int> $number
     *
     * @throws \InvalidArgumentException
     */
    public static function check(int|array $number): void
    {
        foreach ((array)$number as $value) {
            new self($value);
        }
    }
}
