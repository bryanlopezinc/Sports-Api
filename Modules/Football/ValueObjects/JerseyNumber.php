<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

final class JerseyNumber
{
    public const MIN = 1;
    public const MAX = 99;
    public const NOT_KNOWN = 1_000;

    public function __construct(private int $number)
    {
        if (!$this->isKnown()) {
            return;
        }

        throw_if(
            $number < self::MIN || $number > self::MAX,
            new \InvalidArgumentException('invalid jersey number')
        );
    }

    public function number(): int
    {
        return $this->number;
    }

    public function isKnown(): bool
    {
        return $this->number !== self::NOT_KNOWN;
    }
}
