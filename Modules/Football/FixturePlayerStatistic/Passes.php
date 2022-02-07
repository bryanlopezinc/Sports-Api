<?php

declare(strict_types=1);

namespace Module\Football\FixturePlayerStatistic;

use App\ValueObjects\PositiveNumber;

final class Passes
{
    public const MAX_PASS_ACCURACY = 100;

    public function __construct(private int $keyPasses, private int $total, private int $acuuracy)
    {
        PositiveNumber::check(func_get_args());

        if ($acuuracy > self::MAX_PASS_ACCURACY) {
            throw new \InvalidArgumentException('Pass accuracy cannot be greater than ' . self::MAX_PASS_ACCURACY);
        }

        if ($keyPasses > $total) {
            throw new \LogicException('Key passes cannot be greater than total passes');
        }
    }

    public function keyPasses(): int
    {
        return $this->keyPasses;
    }

    public function accuracy(): int
    {
        return $this->acuuracy;
    }

    public function total(): int
    {
        return $this->total;
    }
}
