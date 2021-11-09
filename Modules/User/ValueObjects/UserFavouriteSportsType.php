<?php

declare(strict_types=1);

namespace Module\User\ValueObjects;

final class UserFavouriteSportsType
{
    public const FOOTBALL   = 'soccer';

    private const VALID_TYPES = [
        self::FOOTBALL,
    ];

    public function __construct(private string $type)
    {
        if (notInArray($type, self::VALID_TYPES)) {
            throw new \InvalidArgumentException('Inavlid favourite sports type given');
        }
    }

    public function isFootballType(): bool
    {
        return $this->type === self::FOOTBALL;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
