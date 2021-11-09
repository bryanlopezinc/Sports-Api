<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use Illuminate\Http\Request;
use App\ValueObjects\ResourceId;
use Module\Football\Collections\LeagueIdsCollection;

final class LeagueId extends ResourceId
{
    public function equals(LeagueId $leagueId): bool
    {
        return $this->equalsId($leagueId);
    }

    public function toCollection(): LeagueIdsCollection
    {
        return new LeagueIdsCollection([$this]);
    }

    /**
     * @throws \RuntimeException
     */
    public static function fromRequest(Request $request = null, string $key = 'id'): self
    {
        return new self(static::getIdFromRequest($request, $key));
    }
}
