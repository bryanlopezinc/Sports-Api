<?php

declare(strict_types=1);

namespace Module\User;

use Module\User\Collections\UserFavouritesCollection;

final class UserFavouritesResponse
{
    public function __construct(private UserFavouritesCollection $favourites, private bool $hasMoreItems)
    {
    }

    public function favourites(): UserFavouritesCollection
    {
        return $this->favourites;
    }

    private function hasMorePages(): bool
    {
        return $this->hasMoreItems;
    }
}
