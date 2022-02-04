<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Stringable;
use App\ValueObjects\Country;
use Module\Football\DTO\League;
use Module\Football\DTO\LeagueSeason;
use Module\Football\Media\UrlGenerator;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\LeagueType;
use App\ValueObjects\NonEmptyString as LeagueName;
use App\DTO\Builder;

final class LeagueBuilder extends Builder
{
    public static function fromLeague(League $league): self
    {
        return new self($league->toArray());
    }

    public function setSeason(LeagueSeason $season): self
    {
        return $this->set('season', $season);
    }

    public function setName(string $name): self
    {
        return $this->set('name', new LeagueName($name));
    }

    public function setCountry(string|Stringable $name): self
    {
        return $this->set('country', new Country((string) $name));
    }

    public function setType(int $type): self
    {
        return $this->set('type', new LeagueType($type));
    }

    public function setLogoUrl(int $leagueId): self
    {
        return $this->set('logo', UrlGenerator::new()->leagueLogo(new LeagueId($leagueId)));
    }

    public function setId(int $id): self
    {
        return $this->set('id', new LeagueId($id));
    }

    public function build(): League
    {
        return new League($this->toArray());
    }
}
