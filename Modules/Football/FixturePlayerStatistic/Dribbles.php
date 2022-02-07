<?php

declare(strict_types=1);

namespace Module\Football\FixturePlayerStatistic;

use App\ValueObjects\PositiveNumber;

final class Dribbles
{
    public function __construct(private int $attempts, private int $successful, private int $dribbledPast)
    {
        PositiveNumber::check(func_get_args());

        if ($successful > $attempts) {
            throw new \LogicException('Dribble attempts must be greater or equal to successful dribbles');
        }
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    public function successful(): int
    {
        return $this->successful;
    }

    public function dribbledPast(): int
    {
        return $this->dribbledPast;
    }
}
