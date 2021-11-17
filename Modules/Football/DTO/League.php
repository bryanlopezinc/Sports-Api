<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\ValueObjects\Url;
use App\ValueObjects\Country;
use App\DTO\DataTransferObject;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\LeagueType;
use Module\Football\ValueObjects\Name;

final class League extends DataTransferObject
{
    protected LeagueId $id;
    protected Url $logo;
    protected Country $country;
    protected Name $name;
    protected LeagueType $type;
    protected LeagueSeason $season;

    public function getSeason(): LeagueSeason
    {
        return $this->season;
    }

    public function getType(): LeagueType
    {
        return $this->type;
    }

    public function getId(): LeagueId
    {
        return $this->id;
    }

    public function getLogoUrl(): Url
    {
        return $this->logo;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }
}
