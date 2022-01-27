<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\ValueObjects\Date;
use App\ValueObjects\Country;
use App\DTO\DataTransferObject;
use Module\Football\Media\ImageUrl;
use Module\Football\PlayerPositionOnGrid;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\ValueObjects\PlayerAge;
use Module\Football\ValueObjects\HeightValue;
use Module\Football\ValueObjects\JerseyNumber;
use App\ValueObjects\NonEmptyString as PlayerName;
use Module\Football\ValueObjects\PlayerPosition;

final class Player extends DataTransferObject
{
    protected PlayerAge $age;
    protected ImageUrl $photo;
    protected Date $birth_date;
    protected HeightValue $height_in_cm;
    protected Country $nationality;
    protected PlayerId $id;
    protected PlayerName $name;
    protected PlayerPosition $position;
    protected PlayerPositionOnGrid $player_position_on_grid;
    protected JerseyNumber $numberOnShirt;

    public function getId(): PlayerId
    {
        return $this->id;
    }

    public function getPosition(): PlayerPosition
    {
        return $this->position;
    }

    public function JerseyNumber(): JerseyNumber
    {
        return $this->numberOnShirt;
    }

    /**
     * This method should be called only when the player dto is used in a fixture lineUp dto.
     */
    public function getPlayerPositionOnGridView(): PlayerPositionOnGrid
    {
        return $this->player_position_on_grid;
    }

    public function height(): HeightValue
    {
        return $this->height_in_cm;
    }

    public function nationality(): Country
    {
        return $this->nationality;
    }

    public function age(): PlayerAge
    {
        return $this->age;
    }

    public function name(): PlayerName
    {
        return $this->name;
    }

    public function photoUrl(): ImageUrl
    {
        return $this->photo;
    }

    public function birthDate(): Date
    {
        return $this->birth_date;
    }
}
