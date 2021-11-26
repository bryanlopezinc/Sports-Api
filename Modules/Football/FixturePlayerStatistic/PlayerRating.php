<?php

declare(strict_types=1);

namespace Module\Football\FixturePlayerStatistic;

final class PlayerRating
{
    public const HIGHEST = 10.0;
    public const LOWEST  = 4.0;

    public function __construct(private float $rating)
    {
        if ($rating > self::HIGHEST || $rating < self::LOWEST) {
            throw new \InvalidArgumentException(
                'Player rating cannot be greater than ' . self::HIGHEST . 'or less than ' . self::LOWEST
            );
        }
    }

    public function rating(): float
    {
        return $this->rating;
    }
}
