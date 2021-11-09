<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use Illuminate\Http\Request;
use App\ValueObjects\ResourceId;

final class FixtureId extends ResourceId
{
    public function equals(FixtureId $fixtureId): bool
    {
        return $this->equalsId($fixtureId);
    }

    /**
     * @throws \RuntimeException
     */
    public static function fromRequest(Request $request = null, string $key = 'id'): self
    {
        return new self(static::getIdFromRequest($request, $key));
    }
}
