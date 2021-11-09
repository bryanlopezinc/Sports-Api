<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;

final class Venue extends DataTransferObject
{
    protected string $name;
    protected string $city;

    public function getName(): string
    {
        return $this->name;
    }

    public function getCityName(): string
    {
        return $this->city;
    }
}
