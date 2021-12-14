<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use Illuminate\Contracts\Pagination\Paginator;

final class PaginatedFavouritesCollection
{
    public function __construct(private FavouritesCollection $collection, private Paginator $pagination)
    {
    }

    public function getCollection(): FavouritesCollection
    {
        return $this->collection;
    }

    public function getPagination(): Paginator
    {
        return $this->pagination;
    }
}
