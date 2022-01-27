<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Stringable;
use App\ValueObjects\Date;
use App\ValueObjects\Country;
use Module\Football\DTO\Team;
use Module\Football\DTO\Coach;
use Module\Football\Media\UrlGenerator;
use App\ValueObjects\NonEmptyString as CoachName;
use Module\Football\ValueObjects\CoachId;
use Module\Football\ValueObjects\CoachAge;

final class CoachBuilder extends Builder
{
    public function dateOfBirth(string $dob): self
    {
        $this->set('date_of_birth', $dob =  new Date($dob));

        return $this->set('age', new CoachAge($dob->toCarbon()->age));
    }

    public function photoUrl(int $coachId): self
    {
        return $this->set('photo_url', UrlGenerator::new()->coachPhoto(new CoachId($coachId)));
    }

    public function name(string $name): self
    {
        return $this->set('name', new CoachName($name));
    }

    public function setCountry(string|Stringable $country): self
    {
        return $this->set('nationality', new Country((string)$country));
    }

    public function id(int $id): self
    {
        return $this->set('id', new CoachId($id));
    }

    public function team(?Team $team): self
    {
        if ($team === null) {
            return $this->set('hasCurrentTeam', false);
        }

        return $this->set('team', $team)->set('hasCurrentTeam', true);
    }

    public function build(): Coach
    {
        return new Coach($this->toArray());
    }
}
