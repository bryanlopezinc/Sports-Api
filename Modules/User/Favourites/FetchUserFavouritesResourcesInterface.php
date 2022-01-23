<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\Utils\PaginationData;
use Module\User\ValueObjects\UserId;

interface FetchUserFavouritesResourcesInterface
{
    public function fetchResources(UserId $userId, PaginationData $pagination): FetchUserFavouritesResourcesResult;
}
