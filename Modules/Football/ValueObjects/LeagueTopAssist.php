<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use Module\Football\DTO\Player;

final class LeagueTopAssist
{
    public function __construct(private Player $player, private int $assists)
    {
        throw_if($assists < 1, \InvalidArgumentException::class, 'League Top Assist assists must be greater than zero');
    }

    public function player(): Player
    {
        return $this->player;
    }

    public function assists(): int
    {
        return $this->assists;
    }
}
