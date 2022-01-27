<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\ValueObjects\Name;

final class Venue
{
    public function __construct(public readonly ?Name $name, public readonly ?string $city)
    {
        if (count(array_filter(func_get_args())) === 1) {
            throw new \LogicException(code: 3000);
        }
    }

    public function isKnown(): bool
    {
        return !is_null($this->name) && !is_null($this->city);
    }

    public static function unknown(): self
    {
        return new self(null, null);
    }
}
