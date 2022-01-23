<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\Utils\PaginationData;
use Illuminate\Pagination\Paginator;
use Module\User\ValueObjects\UserId;
use Illuminate\Database\Eloquent\Model;
use Module\User\Favourites\Models\Favourite;

final class FavouritesRepository
{
    /**
     * @return Paginator<Model>
     */
    public function getFavourites(UserId $userId, PaginationData $pagination): Paginator
    {
        return Favourite::select(['favourite_id', 'type'])
            ->join('users_favourites_football', 'users_favourites.record_id', '=', 'users_favourites_football.uid')
            ->where('users_favourites.user_id', $userId->toInt())
            ->simplePaginate($pagination->getPerPage(), page: $pagination->getPage());
    }
}
