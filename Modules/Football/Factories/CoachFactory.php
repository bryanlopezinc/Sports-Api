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
            ->id($id = $this->getIncrementingId())
            ->dateOfBirth(now()->subYears(40)->toDateString())
            ->name($this->faker->name)
            ->photoUrl($id)
            ->setCountry('Germany')
            ->team(TeamFactory::new()->toDto())
            ->toArray();
    }

    public function toDto(): Coach
    {
        return $this->mapToDto();
    }
}
