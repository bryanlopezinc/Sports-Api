<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use Module\User\Collections\UserFavouritesCollection;

final class FavouritesResponse
{
    public function __construct(private ResourcesCollection $favourites, private bool $hasMoreItems)
    {
    }

    public function favourites(): ResourcesCollection
    {
        return $this->favourites;
    }

    public function hasMorePages(): bool
    {
        return $this->hasMoreItems;
    }
}
