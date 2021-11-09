<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;
final class LeagueType
{
    public const LEAGUE = 60;
    public const CUP = 61;

    public function __construct(private int $type)
    {
        if (notInArray($type, [self::LEAGUE, self::CUP])) {
            throw new LogicException('Invalid league type ' . $type);
        }
    }

    public function type(): int
    {
        return $this->type;
    }
}
