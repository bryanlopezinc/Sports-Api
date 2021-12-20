<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use App\ValueObjects\Country;
use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\Collections\TeamsCollection;

final class TeamFactory extends Factory
{
    protected string $dtoClass = Team::class;

    public function definition()
    {
        return (new TeamBuilder)
            ->setId($id = $this->getIncrementingId())
            ->setLogoUrl($id)
            ->setYearFounded(today()->year - 2)
            ->setCountry(collect(Country::VALID)->random())
            ->setName($this->faker->company)
            ->setIsNational(false)
            ->toArray();
    }

    public function toDto(): Team
    {
        return $this->mapToDto();
    }

    public function toCollection(): TeamsCollection
    {
        return $this->mapToCollection(TeamsCollection::class);
    }
}
