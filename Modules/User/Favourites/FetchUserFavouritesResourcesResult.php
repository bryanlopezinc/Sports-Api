<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use Illuminate\Pagination\Paginator;

final class FetchUserFavouritesResourcesResult
{
    public function __construct(
        public readonly FavouritesCollection $favourites,
        public readonly Paginator $paginator
    ) {
    }
}
