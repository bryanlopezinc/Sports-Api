<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Ramsey\Uuid\Uuid;

final class Uid
{
    public function __construct(public readonly string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid uid ' . $value);
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
