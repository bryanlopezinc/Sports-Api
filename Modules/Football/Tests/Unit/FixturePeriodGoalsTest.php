<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit;

use Module\Football\FixturePeriodGoals;
use Module\Football\ValueObjects\MatchGoals;
use Tests\TestCase;

class FixturePeriodGoalsTest extends TestCase
{
    public function test_will_throw_exception_when_only_goal_home_is_given(): void
    {
        $this->expectExceptionCode(3000);

        new FixturePeriodGoals(new MatchGoals(4), null);
    }

    public function test_will_throw_exception_when_only_goals_away_is_given(): void
    {
        $this->expectExceptionCode(3000);

        new FixturePeriodGoals(null, new MatchGoals(4));
    }

    public function test_is_available(): void
    {
        $this->assertFalse((new FixturePeriodGoals(null, null))->isAvailable());
        $this->assertTrue((new FixturePeriodGoals(new MatchGoals(4), new MatchGoals(4)))->isAvailable());
    }
}
