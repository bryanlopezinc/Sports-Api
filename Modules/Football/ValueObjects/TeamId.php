<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use Illuminate\Http\Request;
use App\ValueObjects\ResourceId;
use Module\Football\Collections\TeamIdsCollection;

final class TeamId extends ResourceId
{
    public function equals(TeamId $teamId): bool
    {
        return $this->equalsId($teamId);
    }

    public function toCollection(): TeamIdsCollection
    {
        return new TeamIdsCollection([$this]);
    }

    /**
     * @throws \RuntimeException
     */
    public static function fromRequest(Request $request = null, string $key = 'id'): self
    {
        return new self(static::getIdFromRequest($request, $key));
    }
}
