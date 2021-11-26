<?php

declare(strict_types=1);

namespace Module\Football\FixturePlayerStatistic;

use App\ValueObjects\NonNegativeNumber;
use Module\Football\ValueObjects\MatchGoals;

final class GoalKeeperGoalsStat
{
    public function __construct(private int $goalsConceded, private int $goalsSaves)
    {
        new MatchGoals($goalsConceded); //validation
        NonNegativeNumber::check($goalsSaves);
    }

    public function goalsConceded(): int
    {
        return $this->goalsConceded;
    }

    public function saves(): int
    {
        return $this->goalsSaves;
    }
}
