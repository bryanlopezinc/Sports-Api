<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\ValueObjects\Country;
use App\DTO\DataTransferObject;
use Module\Football\Venue;
use Module\Football\Media\ImageUrl;
use Module\Football\ValueObjects\Name;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\TeamYearFounded;

final class Team extends DataTransferObject
{
    protected TeamId $id;
    protected ImageUrl $logo;
    protected TeamYearFounded $founded;
    protected Country $country;
    protected Venue $venue;
    protected Name $name;
    protected bool $national;
    protected bool $has_year_founded_info;

    public function getId(): TeamId
    {
        return $this->id;
    }

    public function getLogoUrl(): ImageUrl
    {
        return $this->logo;
    }

    public function getVenue(): Venue
    {
        return $this->venue;
    }

    /**
     * The yearFounded is not always available for all fixtures.
     * Ensure yearFounded is available by using the hasYearFoundedInfo method
     */
    public function getYearFounded(): TeamYearFounded
    {
        return $this->founded;
    }

    public function hasYearFoundedInfo(): bool
    {
        return $this->has_year_founded_info;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function isNationalTeam(): bool
    {
        return $this->national;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }
}
