<?php

declare(strict_types=1);

namespace Module\User\Database;

final class Column
{
    public const USERNAME   = 'username';
    public const ID         = 'id';
    public const NAME       = 'name';
    public const EMAIL      = 'email';
    public const PASSWORD   = 'password';
    public const IS_PRIVATE = 'is_private';

    public const FAVOURITES_COUNT = 'favourites_count';
}
