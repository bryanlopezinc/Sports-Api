<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use App\ValueObjects\Country;
use Module\Football\DTO\League;
use Module\Football\ValueObjects\LeagueType;
use Module\Football\DTO\Builders\LeagueBuilder;
use Module\Football\Collections\LeaguesCollection;

final class LeagueFactory extends Factory
{
    protected string $dtoClass = League::class;

    public function definition()
    {
        return (new LeagueBuilder)
            ->setId($id = $this->getIncrementingId())
            ->setLogoUrl($id)
            ->setType(LeagueType::LEAGUE)
            ->setCountry(collect(Country::VALID)->random())
            ->setName($this->faker->company)
            ->setSeason(LeagueSeasonFactory::new()->toDto())
            ->toArray();
    }

    public function toDto(): League
    {
        return $this->mapToDto();
    }

    public function toCollection(): LeaguesCollection
    {
        return $this->mapToCollection(LeaguesCollection::class);
    }
}
