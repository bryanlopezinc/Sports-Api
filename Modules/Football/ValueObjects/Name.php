<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

final class Name
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
