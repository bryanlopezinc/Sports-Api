<?php

declare(strict_types=1);

namespace Module\User\Contracts;

use App\Utils\PaginationData;
use Module\User\ValueObjects\UserId;
use Module\User\PaginatedUserFavouriteTypes;

interface FetchUserFavouritesRepositoryInterface
{
    public function getFavourites(UserId $userId, PaginationData $pagination): PaginatedUserFavouriteTypes;
}
