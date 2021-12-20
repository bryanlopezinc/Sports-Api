<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\Collections\BaseCollection;
use Illuminate\Support\Collection;
use Module\User\Favourites\Dto\Favourite;
use Module\User\Favourites\Football\FavouritesCollection as FootballSportTypeCollection;

final class FavouritesCollection extends BaseCollection
{
    protected function isValid($value): bool
    {
        return $value instanceof Favourite;
    }

    public function whereSportTypeIsfootball(): FootballSportTypeCollection
    {
        return $this->collection
            ->filter(fn (Favourite $favourite): bool => $favourite->sportType()->isFootball())
            ->pipe(fn (Collection $collection): FootballSportTypeCollection => new FootballSportTypeCollection($collection->all()));
    }
}
