<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class NonNegativeNumber
{
    public function __construct(private int $number)
    {
        if ($number < 0) {
            throw new \InvalidArgumentException('Number cannot be less than 0');
        }
    }

    public static function fromInt(int $number): self
    {
        return new self($number);
    }

    /**
     * Throw an exception if number is negative
     *
     * @throws \InvalidArgumentException
     */
    public static function check(int $number): void
    {
        new self($number);
    }

    public function number(): int
    {
        return $this->number;
    }
}
