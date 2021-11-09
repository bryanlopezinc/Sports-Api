<?php

declare(strict_types=1);

namespace Module\User\Contracts;

use Module\User\Collections\UserFavouritesCollection;
use Module\User\Collections\UserFavouriteTypesCollection;

interface FetchUserFavouritesResourcesRepositoryInterface
{
    public function fetch(UserFavouriteTypesCollection $collection): UserFavouritesCollection;
}