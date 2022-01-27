<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\ValueObjects\MatchGoals;

final class FixturePeriodGoals
{
    public function __construct(private ?MatchGoals $goalsHome, private ?MatchGoals $goalsAway)
    {
        if (count(array_filter(func_get_args())) === 1) {
            throw new \LogicException('Cannot have only one team goals', 3000);
        }
    }

    public function isAvailable(): bool
    {
        return !is_null($this->goalsHome) && !is_null($this->goalsAway);
    }

    public function goalsHome(): MatchGoals
    {
        return $this->goalsHome;
    }

    public function goalsAway(): MatchGoals
    {
        return $this->goalsAway;
    }
}
