<?php

declare(strict_types=1);

namespace Module\Football\FixtureStatistic;

final class BallPossesion extends AbstractStatistic
{
    public const MAX_VALUE = 100;

    public function name(): string
    {
        return self::BALL_POSSESION;
    }

    protected function validate(): void
    {
        if ($this->value > self::MAX_VALUE) {
            throw new \InvalidArgumentException(
                'Ball Possession cannot be greater than ' . self::MAX_VALUE
            );
        }
    }
}
