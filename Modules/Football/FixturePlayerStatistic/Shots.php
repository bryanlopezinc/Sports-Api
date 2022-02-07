<?php

declare(strict_types=1);

namespace Module\Football\FixturePlayerStatistic;

use App\ValueObjects\PositiveNumber;

final class Shots
{
    public function __construct(private int $onTarget, private int $total)
    {
        PositiveNumber::check(func_get_args());

        if ($onTarget > $total) {
            throw new \LogicException('Shots on target cannot be greater than total');
        }
    }

    public function onTarget(): int
    {
        return $this->onTarget;
    }

    public function total(): int
    {
        return $this->total;
    }
}
