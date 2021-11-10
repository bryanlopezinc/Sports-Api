<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\Factories\PlayerFactory;
use Module\Football\ValueObjects\LeagueTopScorer;

class LeagueTopScorerTest extends TestCase
{
    public function test_goals_cannot_be_less_than_one(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new LeagueTopScorer(PlayerFactory::new()->toDto(), -1);
    }

    public function test_goals_must_greater_than_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new LeagueTopScorer(PlayerFactory::new()->toDto(), 0);
    }
}
