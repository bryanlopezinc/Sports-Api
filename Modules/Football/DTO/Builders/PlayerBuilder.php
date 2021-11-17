<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use App\ValueObjects\Url;
use App\ValueObjects\Date;
use App\ValueObjects\Country;
use Module\Football\DTO\Player;
use Module\Football\PlayerPositionOnGrid;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\ValueObjects\PlayerAge;
use Module\Football\ValueObjects\HeightValue;
use Module\Football\ValueObjects\JerseyNumber;
use Module\Football\ValueObjects\Name;
use Module\Football\ValueObjects\PlayerPosition;
use Stringable;

final class PlayerBuilder extends Builder
{
    public static function fromPlayer(Player $player): self
    {
        return new self($player->toArray());
    }

    public function setPhotoUrl(string $url): self
    {
        return $this->set('photo', Url::fromString($url));
    }

    public function setDateOfBirth(string $dateOfBirth): self
    {
        $this->set('birth_date', $dob = new Date($dateOfBirth));

        return $this->set('age', PlayerAge::fromInt($dob->toCarbon()->age));
    }

    /**
     * Set player height (in cm)
     */
    public function setHeight(float $height): self
    {
        return $this->set('height_in_cm', HeightValue::make($height));
    }

    public function setNationality(string|Stringable $countryName): self
    {
        return $this->set('nationality', new Country((string) $countryName));
    }

    public function setId(int $id): self
    {
        return $this->set('id', new PlayerId($id));
    }

    public function setName(string $name): self
    {
        return $this->set('name', new Name($name));
    }

    public function setPosition(int $position): self
    {
        return $this->set('position', new PlayerPosition($position));
    }

    public function setNumberOnShirt(?int $number): self
    {
                if ($number === null) {
            return $this->set('numberOnShirt', new JerseyNumber(JerseyNumber::NOT_KNOWN));
        }

        return $this->set('numberOnShirt', new JerseyNumber($number));
    }

    public function setPositionOnGridLineUp(?int $row, ?int $column): self
    {
        return $this->set('player_position_on_grid', new PlayerPositionOnGrid($row, $column));
    }

    public function build(): Player
    {
        return new Player($this->toArray());
    }
}
