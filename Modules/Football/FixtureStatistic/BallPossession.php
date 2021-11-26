<?php

declare(strict_types=1);

namespace Module\Football\FixtureStatistic;

final class BallPossession
{
    public const MAX_VALUE = 100;

    public function __construct(private int $ballPossession)
    {
        if ($ballPossession > self::MAX_VALUE) {
            throw new \InvalidArgumentException(
                'Ball Possession cannot be greater than ' . self::MAX_VALUE
            );
        }
    }

    public function value(): int
    {
        return $this->ballPossession;
    }
}
