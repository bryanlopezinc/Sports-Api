<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\PlayerStatistics;
use Module\Football\DTO\Builders\PlayerStatisticBuilder;
use Module\Football\Collections\FixturePlayersStatisticsCollection;

final class PlayerStatisticsFactory extends Factory
{
    protected string $dtoClass = PlayerStatistics::class;

    public function definition()
    {
        return (new PlayerStatisticBuilder())
            ->cards(0, 1)
            ->dribbles(5, 2, 1)
            ->goals(0, 1)
            ->interceptions(2)
            ->minutesPlayed(50)
            ->offsides(0)
            ->passes(1, 3, 50)
            ->player(PlayerFactory::new()->midfielder()->toDto())
            ->rating(5.0)
            ->shots(1, 3)
            ->team(TeamFactory::new()->toDto())
            ->toArray();
    }

    public function toDto(): PlayerStatistics
    {
        return $this->mapToDto();
    }

    public function toCollection(): FixturePlayersStatisticsCollection
    {
        return $this->mapToCollection(FixturePlayersStatisticsCollection::class);
    }
}
