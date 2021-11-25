<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\Coach;
use Module\Football\DTO\Builders\CoachBuilder;

final class CoachFactory extends Factory
{
    protected string $dtoClass = Coach::class;

    public function definition()
    {
        return (new CoachBuilder())
            ->id($this->getIncrementingId())
            ->dateOfBirth(now()->subYears(40)->toDateString())
            ->name($this->faker->name)
            ->photoUrl($this->faker->url)
            ->setCountry('Germany')
            ->team(TeamFactory::new()->toDto())
            ->toArray();
    }

    public function toDto(): Coach
    {
        return $this->mapToDto();
    }
}
