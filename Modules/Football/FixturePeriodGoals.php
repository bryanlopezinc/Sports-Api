<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\ValueObjects\MatchGoals;

final class FixturePeriodGoals
{
    public function __construct(private MatchGoals $goalsHome, private MatchGoals $goalsAway)
    {
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
