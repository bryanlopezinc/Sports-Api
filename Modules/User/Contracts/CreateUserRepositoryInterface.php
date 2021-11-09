<?php

declare(strict_types=1);

namespace Module\User\Contracts;

use Module\User\Dto\User;

interface CreateUserRepositoryInterface
{
    /**
     * @throws \Module\User\Exceptions\PasswordNotHashedException
     */
    public function create(User $user): User;
}
