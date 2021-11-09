<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\DTO;

use LogicException;
use Tests\TestCase;
use Module\Football\Factories\StandingDataFactory;
use Module\Football\DTO\Builders\StandingDataBuilder;

class StandingDataDtoTest extends TestCase
{
    public function test_games_played_must_equal_wins_draws_and_losses(): void
    {
        $this->expectException(LogicException::class);

        StandingDataFactory::new()
            ->withState(fn (StandingDataBuilder $b) => $b->setMatchesPlayed(50))
            ->toDto();
    }
}
