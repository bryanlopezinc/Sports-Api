<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\DTO\Player;
use Module\Football\ValueObjects\ReasonForMissingFixture;

/**
 * A player that missed (or will miss) a fixture for various reasosns
 */
final class TeamMissingPlayer
{
    public function __construct(private Player $player, private ReasonForMissingFixture $reason)
    {
    }

    public function player(): Player
    {
        return $this->player;
    }

    public function reasonForMissingFixture(): ReasonForMissingFixture
    {
        return $this->reason;
    }
}
