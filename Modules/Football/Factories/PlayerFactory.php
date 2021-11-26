<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use App\ValueObjects\Country;
use Module\Football\DTO\Player;
use Module\Football\ValueObjects\PlayerAge;
use Module\Football\ValueObjects\HeightValue;
use Module\Football\ValueObjects\JerseyNumber;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\PlayerPosition;
use Module\Football\Collections\PlayersCollection;

final class PlayerFactory extends Factory
{
    protected string $dtoClass = Player::class;

    public function definition()
    {
        $positions = [
            PlayerPosition::ATTACKER,
            PlayerPosition::DEFENDER,
            PlayerPosition::GOALIE,
            PlayerPosition::MIDFIELDER
        ];

        return (new PlayerBuilder)
            ->setId($this->getIncrementingId())
            ->setPhotoUrl($this->faker->url)
            ->setDateOfBirth(now()->subYears(rand(PlayerAge::MIN, PlayerAge::MAX))->toDateString())
            ->setPosition(collect($positions)->shuffle()->first())
            ->setHeight(collect(range(HeightValue::MIN_HEIGHT_CM, HeightValue::MAX_HEIGHT_CM))->random())
            ->setNationality(collect(Country::VALID)->random())
            ->setNumberOnShirt(rand(JerseyNumber::MIN, JerseyNumber::MAX))
            ->setName($this->faker->name)
            ->toArray();
    }

    public function midfielder(): self
    {
        return $this->state(function (array $attributes) {
            return (new PlayerBuilder($attributes))->setPosition(PlayerPosition::MIDFIELDER)->toArray();
        });
    }

    public function toDto(): Player
    {
        return $this->mapToDto();
    }

    public function toCollection(): PlayersCollection
    {
        return $this->mapToCollection(PlayersCollection::class);
    }
}
