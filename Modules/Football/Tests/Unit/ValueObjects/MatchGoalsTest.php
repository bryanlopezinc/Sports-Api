<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\MatchGoals;

class MatchGoalsTest extends TestCase
{
    public function test_throws_exception_when_goals_is_less_than_0(): void
    {
        $this->expectException(\LogicException::class);

        new MatchGoals(-1);
    }

    public function test_throws_exception_when_goals_is_greatear_than_max_goals(): void
    {
        $this->expectException(\LogicException::class);

        new MatchGoals(MatchGoals::MAX + 1);
    }
}
