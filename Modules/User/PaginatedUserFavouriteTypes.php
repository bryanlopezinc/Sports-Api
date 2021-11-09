<?php

declare(strict_types=1);

namespace Module\User;

use Illuminate\Contracts\Pagination\Paginator;
use Module\User\Collections\UserFavouriteTypesCollection;

final class PaginatedUserFavouriteTypes
{
    public function __construct(private UserFavouriteTypesCollection $collection, private Paginator $pagination)
    {
    }

    public function getCollection(): UserFavouriteTypesCollection
    {
        return $this->collection;
    }

    public function getPagination(): Paginator
    {
        return $this->pagination;
    }
}
