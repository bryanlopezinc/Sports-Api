<?php

declare(strict_types=1);

namespace Module\Football;

final class FixtureReferee
{
    public const NOT_KNOWN = 'Unavailable';

    public function __construct(private string $name)
    {
    }

    public function nameIsAvailable(): bool
    {
        return $this->name !== self::NOT_KNOWN;
    }

    public function name(): string
    {
        return $this->name;
    }
}
