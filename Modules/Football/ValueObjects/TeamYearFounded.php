<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;

final class TeamYearFounded
{
    /** @see https://en.wikipedia.org/wiki/Sheffield_F.C. */
    public const MIN_YEAR = 1857;

    public function __construct(private int $yearFounded)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->yearFounded < self::MIN_YEAR) {
            throw new LogicException('Year founded must not be less than ' . self::MIN_YEAR, 400);
        }

        if ($this->yearFounded > today()->year) {
            throw new LogicException('Year founded must not be greater than ' . today()->year, 401);
        }
    }

    public function toInt(): int
    {
        return $this->yearFounded;
    }
}
