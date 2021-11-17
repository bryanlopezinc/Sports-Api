<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\Football\ValueObjects\Name;

final class Venue extends DataTransferObject
{
    protected Name $name;
    protected string $city;

    public function getName(): Name
    {
        return $this->name;
    }

    public function getCityName(): string
    {
        return $this->city;
    }
}
