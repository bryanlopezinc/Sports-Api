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
     * @throws \OutOfBoundsException
     * @throws \Illuminate\Collections\MultipleItemsFoundException
     */
    public function sole(): User
    {
        return $this->soleItem();
    }
}
