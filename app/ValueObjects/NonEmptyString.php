<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class NonEmptyString
{
    public function __construct(private string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new \InvalidArgumentException('Entity name cannot be empty');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
