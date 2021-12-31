<?php

declare(strict_types=1);

namespace Module\Football;

use App\ValueObjects\Date;
use Module\Football\DTO\Team;

final class PlayerTransferRecord
{
    public function __construct(
        private Date $date,
        private ?Team $teamDeparted,
        private ?Team $teamJoined
    ) {
        if ($teamDeparted === null && $teamJoined === null) {
            throw new \LogicException('Invalid player transfer record');
        }
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function teamDepartedIsKnown(): bool
    {
        return $this->teamDeparted !== null;
    }

    public function teamJoinedIsKnown(): bool
    {
        return $this->teamJoined !== null;
    }

    public function teamDeparted(): Team
    {
        return $this->teamDeparted;
    }

    public function teamJoined(): Team
    {
        return $this->teamJoined;
    }
}
