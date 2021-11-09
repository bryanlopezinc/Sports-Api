<?php

declare(strict_types=1);

namespace Module\User\Collections;

use Module\Football\DTO\Team;
use Module\Football\DTO\League;
use App\Collections\BaseCollection;

final class UserFavouritesCollection extends BaseCollection
{
    protected function isValid($value): bool
    {
        return $value instanceof Team ||
               $value instanceof League;
    }
}
