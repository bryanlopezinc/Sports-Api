<?php

declare(strict_types=1);

namespace Module\Football\FixturePlayerStatistic;

use App\ValueObjects\PositiveNumber;
use Module\Football\ValueObjects\MatchGoals;

final class Goals
{
    public function __construct(private int $goals, private int $assists)
    {
        new MatchGoals($goals); //validation
        PositiveNumber::check($assists);
    }

    public function goals(): int
    {
        return $this->goals;
    }

    public function assists(): int
    {
        return $this->assists;
    }
}
