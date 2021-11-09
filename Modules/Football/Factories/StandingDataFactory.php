<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\StandingData;
use Module\Football\DTO\Builders\StandingDataBuilder;

final class StandingDataFactory extends Factory
{
    protected string $dtoClass = StandingData::class;

    public function definition()
    {
        $win = 20;
        $draw = 3;
        $lose = 2;

        return (new StandingDataBuilder)
            ->setMatchedWon($win)
            ->setMatchesDrawn($draw)
            ->setMatchesLost($lose)
            ->setMatchesPlayed($win + $draw + $lose)
            ->setGoalsAgainst(3)
            ->setGoalsFound(30)
            ->toArray();
    }

    /**
     * The callable accepts a StandingDataBuilder instance
     * and should return a StandingDataBuilder instance
     */
    public function withState(callable $state): self
    {
        return parent::state(function (array $attributes) use ($state) {
            return $state(new StandingDataBuilder($attributes))->toArray();
        });
    }

    public function toDto(): StandingData
    {
        return $this->mapToDto();
    }
}
