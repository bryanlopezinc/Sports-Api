<?php

declare(strict_types=1);

namespace Module\User\Collections;

use App\Collections\BaseCollection;
use Module\User\Dto\User;

/**
 * @template T of User
 */
final class UsersCollection extends BaseCollection
{
    protected function isValid(mixed $value): bool
    {
        return $value instanceof User;
    }

    /**
     * @throws \Illuminate\Support\ItemNotFoundException
     * @throws \Illuminate\Support\MultipleItemsFoundException
     */
    public function sole(): User
    {
        return $this->soleItem();
    }
}
