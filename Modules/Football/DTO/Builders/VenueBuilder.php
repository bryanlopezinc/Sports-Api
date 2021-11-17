<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\Venue;
use Module\Football\ValueObjects\Name;

final class VenueBuilder extends Builder
{
    public function setName(?string $name): self
    {
        return $this->set('name', new Name($name));
    }

    public function setCity(?string $city): self
    {
        return $this->set('city', $city);
    }

    public function build(): Venue
    {
        return new Venue($this->attributes);
    }
}
