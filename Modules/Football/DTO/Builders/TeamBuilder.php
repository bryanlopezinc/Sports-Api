<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Stringable;
use App\ValueObjects\Url;
use App\ValueObjects\Country;
use Module\Football\DTO\Team;
use Module\Football\DTO\Venue;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\TeamYearFounded;

final class TeamBuilder extends Builder
{
    public static function fromTeam(Team $team): static
    {
        return new static($team->toArray());
    }

    public function setName(string $name): self
    {
        return $this->set('name', $name);
    }

    public function setIsNational(bool $isNational): self
    {
        return $this->set('national', $isNational);
    }

    public function setVenue(Venue $venue): self
    {
        return $this->set('venue', $venue);
    }

    public function setCountry(string|Stringable $country): self
    {
        return $this->set('country', new Country((string)$country));
    }

    public function setYearFounded(int $founded): self
    {
        return $this->set('founded', new TeamYearFounded($founded));
    }

    public function setHasYearFounded(bool $hasYearFounded): self
    {
        return $this->set('has_year_founded_info', $hasYearFounded);
    }

    public function setLogoUrl(string $url): self
    {
        return $this->set('logo', new Url($url));
    }

    public function setId(int $id): self
    {
        return $this->set('id', new TeamId($id));
    }

    public function build(): Team
    {
        return new Team($this->attributes);
    }
}
