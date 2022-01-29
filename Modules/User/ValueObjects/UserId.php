<?php

declare(strict_types=1);

namespace Module\User\ValueObjects;

use Illuminate\Http\Request;
use App\ValueObjects\ResourceId;
use Module\User\Collections\UserIdsCollection;

final class UserId extends ResourceId
{
    public function asCollection(): UserIdsCollection
    {
        return UserIdsCollection::fromId($this);
    }

    /**
     * @throws \RuntimeException
     */
    public static function fromRequest(Request $request = null, string $key = 'id'): self
    {
        return new self(static::getIdFromRequest($request, $key));
    }

    public static function fromAuthUser(): self
    {
        return new self(auth('api')->id());
    }
}
