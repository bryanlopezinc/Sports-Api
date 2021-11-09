<?php

declare(strict_types=1);

namespace Module\User\Collections;

use App\Collections\BaseCollection;
use Module\User\ValueObjects\UserId;

/**
 * @template T of UserId
 */
final class UserIdsCollection extends BaseCollection
{
    protected function isValid(mixed $item): bool
    {
        return $item instanceof UserId;
    }

    /**
     * @return array<int>
     */
    public function toIntegerArray(): array
    {
        return $this->collection->map(fn (UserId $userId): int => $userId->toInt())->all();
    }

    public static function fromId(UserId $userId): self
    {
        return new static([$userId]);
    }
}
