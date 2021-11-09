<?php

declare(strict_types=1);

namespace Module\User\Collections;

use Module\User\Dto\User;
use App\Collections\DtoCollection;

/**
 * @template T of User
 */
final class UsersCollection extends DtoCollection
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
