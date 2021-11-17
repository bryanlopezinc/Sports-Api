<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use App\ValueObjects\Url;
use App\ValueObjects\Date;
use Module\Football\DTO\Team;
use Module\Football\DTO\Coach;
use Module\Football\ValueObjects\CoachAge;
use Module\Football\ValueObjects\CoachId;
use Module\Football\ValueObjects\Name;

final class CoachBuilder extends Builder
{
    public function dateOfBirth(string $dob): self
    {
        $this->set('date_of_birth', $dob =  new Date($dob));

        return $this->set('age', new CoachAge($dob->toCarbon()->age));
    }

    public function photoUrl(string $url): self
    {
        return $this->set('photo_url', Url::fromString($url));
    }

    public function name(string $name): self
    {
        return $this->set('name', new Name($name));
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
