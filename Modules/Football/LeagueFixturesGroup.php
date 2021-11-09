<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\DTO\League;

/**
 * A group of league and the number of fixtures count
 */
final class LeagueFixturesGroup
{
    public function __construct(private League $league, private int $fixturesCount)
    {
    }

    public function league(): League
    {
        return $this->league;
    }

    public function fixturesCount(): int
    {
        return $this->fixturesCount;
    }
}
