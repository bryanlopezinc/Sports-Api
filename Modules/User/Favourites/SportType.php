<?php

declare(strict_types=1);

namespace Module\User\Favourites;

final class SportType
{
    public const FOOTBALL = 'soccer';

    private const VALID_TYPES = [
        self::FOOTBALL,
    ];

    public function __construct(private string $type)
    {
        if (notInArray($type, self::VALID_TYPES)) {
            throw new \InvalidArgumentException('Inavlid favourite sports type given');
        }
    }

    public function isFootball(): bool
    {
        return $this->type === self::FOOTBALL;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
