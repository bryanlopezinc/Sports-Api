<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use Tests\TestCase;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\PlayerStatisticsFactory;
use Module\Football\DTO\Builders\PlayerStatisticBuilder;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

class FixturePlayersStatisticsCollectionTest extends TestCase
{
    public function test_can_only_contain_one_or_two_team(): void
    {
        $this->expectExceptionCode(1500);

        PlayerStatisticsFactory::new()->count(5)->toCollection();
    }

    public function test_can_only_contain_unique_players(): void
    {
        $this->expectExceptionCode(4000);

        $buider = new PlayerStatisticBuilder();

        $collection = PlayerStatisticsFactory::new()
            ->count(5)
            ->sequence($buider->team(TeamFactory::new()->toDto())->toArray())
            ->toCollection()
            ->toLaravelCollection();

        (new FixturePlayersStatisticsCollection($collection->merge($collection)));
    }
}
