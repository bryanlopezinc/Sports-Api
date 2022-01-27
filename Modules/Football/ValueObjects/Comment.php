<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

final class Comment
{
    public const MAX = 150;

    public function __construct(public readonly string $value)
    {
        new Name($value);

        if (\mb_strlen($value) > self::MAX) {
            throw new \InvalidArgumentException('Comment cannot exceed ' . self::MAX, 505);
        }
    }
}
