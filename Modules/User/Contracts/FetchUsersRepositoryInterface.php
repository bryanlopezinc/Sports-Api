<?php

declare(strict_types=1);

namespace Module\User\Contracts;

use App\ValueObjects\Email;
use Module\User\QueryFields;
use Module\User\ValueObjects\Username;
use Module\User\Collections\UsersCollection;
use Module\User\Collections\UserIdsCollection;

interface FetchUsersRepositoryInterface
{
    public function findUsersById(UserIdsCollection $ids, QueryFields $options): UsersCollection;

    /**
     * @param array<Email> $emails
     */
    public function findUsersByEmail(array $emails, QueryFields $options): UsersCollection;

    /**
     * @param array<Username> $usernames
     */
    public function findUsersByUsername(array $usernames, QueryFields $options): UsersCollection;
}
