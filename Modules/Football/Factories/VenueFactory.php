<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\Venue;
use Module\Football\DTO\Builders\VenueBuilder;

final class VenueFactory extends Factory
{
    protected string $dtoClass = Venue::class;

    public function definition()
    {
        return (new VenueBuilder)
            ->setName($this->faker->company)
            ->setCity($this->faker->city)
            ->toArray();
    }

    public function toDto(): Venue
    {
        return $this->mapToDto();
    }
}
