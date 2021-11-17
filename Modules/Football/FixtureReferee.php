<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\ValueObjects\Name;

final class FixtureReferee
{
    public const NOT_KNOWN = 'Unavailable';

    private Name $name;

    public function __construct(string $name)
    {
        $this->name = new Name($name);
    }

    public function nameIsAvailable(): bool
    {
        return $this->name->value() !== self::NOT_KNOWN;
    }

    public function name(): string
    {
        return $this->name->value();
    }
}
