<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use App\ValueObjects\ResourceId;
use Illuminate\Http\Request;

final class CoachId extends ResourceId
{
    /**
     * @throws \RuntimeException
     */
    public static function fromRequest(Request $request = null, string $key = 'id'): self
    {
        return new self(static::getIdFromRequest($request, $key));
    }
}
