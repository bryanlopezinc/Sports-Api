<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use JsonSerializable;
use App\Utils\Config;
use Module\Football\Exceptions\InvalidSeasonException;

final class Season implements JsonSerializable
{
    public function __construct(private int $season)
    {
        $this->validate();
    }

    public static function make(int $season): static
    {
        return new static($season);
    }

    public static function fromString(string $season): static
    {
        return new self((int) $season);
    }

    protected function validate(): void
    {
        if ($this->season < $this->minSeason()) {
            throw new InvalidSeasonException();
        }
    }

    public static function minSeason(): int
    {
        return Config::get('football.minSeason');
    }

    public function toInt(): int
    {
        return $this->season;
    }

    public function jsonSerialize()
    {
        return $this->toInt();
    }

    public function equals(Season $season): bool
    {
        return $this->season === $season->toInt();
    }
}
