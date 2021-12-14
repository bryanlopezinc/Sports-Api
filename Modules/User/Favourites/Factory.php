<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use Module\User\Favourites\Models\Favourite;
use Module\User\Favourites\Models\FavouriteType;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

final class Factory extends BaseFactory
{
    protected $model = Favourite::class;

    public function definition()
    {
        return [
            'type_id' => function (array $attributes) {
                return FavouriteType::where([
                    'sports_type'   => FavouriteType::SPORTS_TYPE_FOOTBALL,
                    'type'          => FavouriteType::TEAM_TYPE,
                ])->first()->id;
            },
        ];
    }

    public function footballLeagueType(): self
    {
        return $this->state(function () {
            return [
                'type_id' => function (array $attributes) {
                    return FavouriteType::where([
                        'sports_type'   => FavouriteType::SPORTS_TYPE_FOOTBALL,
                        'type'          => FavouriteType::LEAGUE_TYPE,
                    ])->first()->id;
                },
            ];
        });
    }

    public function favouriteId(int $id): self
    {
        return $this->state(fn () => ['favourite_id' => $id]);
    }
}
