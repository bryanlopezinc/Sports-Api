<?php

declare(strict_types=1);

namespace Module\Football;

use Module\Football\DTO\Player;
use Module\Football\PlayerTransferRecord;

final class PlayerTransferHistory
{
    /**
     * @param array<PlayerTransferRecord> $transfers
     */
    public function __construct(private Player $player, private array $transfers)
    {
    }

    public function player(): Player
    {
        return $this->player;
    }

    /**
     * @return array<PlayerTransferRecord>
     */
    public function transfers(): array
    {
        return $this->transfers;
    }
}
