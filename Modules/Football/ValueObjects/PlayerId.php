<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use App\ValueObjects\ResourceId;

final class PlayerId extends ResourceId
{
    public function equals(PlayerId $playerId): bool
    {
        return $this->equalsId($playerId);
    }
}
