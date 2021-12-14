<?php

declare(strict_types=1);

namespace Module\User\Favourites;

final class Type
{
    public const TEAM   = 'team';
    public const LEAGUE = 'league';

    private const VALID_TYPES = [
        self::TEAM,
        self::LEAGUE
    ];

    public function __construct(private string $type)
    {
        if (notInArray($type, self::VALID_TYPES)) {
            throw new \InvalidArgumentException('Inavlid favourite type given');
        }
    }

    public function isTeamType(): bool
    {
        return $this->type === self::TEAM;
    }

    public function isLeagueType(): bool
    {
        return $this->type === self::LEAGUE;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
