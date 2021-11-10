<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use Module\Football\DTO\Player;

final class LeagueTopScorer
{
    public function __construct(private Player $player, private int $goals)
    {
        throw_if($goals < 1, \InvalidArgumentException::class, 'League Top scorer goals must be greater than zero');
    }

    public function player(): Player
    {
        return $this->player;
    }

    public function leagueGoals(): int
    {
        return $this->goals;
    }
}
