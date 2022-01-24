<?php

declare(strict_types=1);

namespace Module\User\Contracts;

use Module\User\QueryFields;
use Module\User\Collections\UsersCollection;
use Module\User\Collections\UserIdsCollection;

interface FetchUsersRepositoryInterface
{
    public function findUsersById(UserIdsCollection $ids, QueryFields $options): UsersCollection;
}
