<?php

declare(strict_types=1);

namespace Module\User\Contracts;

use Module\User\Dto\UserFavourite;

interface CreateUserFavouriteRepositoryInterface
{
    public function create(UserFavourite $favourite): bool;

    public function exists(UserFavourite $favourite): bool;
}
