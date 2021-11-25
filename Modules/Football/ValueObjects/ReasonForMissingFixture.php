<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

final class ReasonForMissingFixture
{
    public const INJURED    = 'Injured';
    public const SUSPENDED  = 'Suspended';
    public const DOUBTFUL   = 'Doubtful';

    public function __construct(private string $reason)
    {
        if (notInArray($reason, [self::DOUBTFUL, self::INJURED, self::SUSPENDED])) {
            throw new \InvalidArgumentException("Invalid reason: $reason");
        }
    }

    public function isInjured(): bool
    {
        return $this->reason === self::INJURED;
    }

    public function isSuspended(): bool
    {
        return $this->reason === self::SUSPENDED;
    }

    public function isDoubtful(): bool
    {
        return $this->reason === self::DOUBTFUL;
    }
}
