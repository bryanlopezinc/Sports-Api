<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;

final class HeightValue
{
    const MIN_HEIGHT_CM = 45.4;
    const MAX_HEIGHT_CM = 500;

    public function __construct(private float $height)
    {
        $this->validate();
    }

    public static function make(float $height): static
    {
        return new static($height);
    }

    private function validate(): void
    {
        if ($this->height < self::MIN_HEIGHT_CM) {
            throw new LogicException(code: 400);
        }

        if ($this->height > self::MAX_HEIGHT_CM) {
            throw new LogicException(code: 401);
        }
    }

    public function height(): float
    {
        return $this->height;
    }
}
