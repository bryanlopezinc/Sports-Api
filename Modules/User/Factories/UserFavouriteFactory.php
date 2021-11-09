<?php

declare(strict_types=1);

namespace Module\User\Factories;

use Module\User\Models\UserFavourite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Module\User\Models\UserFavouriteType as UserFavouriteTypeModel;

final class UserFavouriteFactory extends Factory
{
    protected $model = UserFavourite::class;

    public function definition()
    {
        return [
            'type_id'          => function (array $attributes) {
                return UserFavouriteTypeModel::where([
                    'sports_type'   => UserFavouriteTypeModel::SPORTS_TYPE_FOOTBALL,
                    'type'          => UserFavouriteTypeModel::TEAM_TYPE,
                ])->first()->id;
            },
        ];
    }

    public function footballLeagueType(): self
    {
        return $this->state(function(){
            return [
                'type_id'          => function (array $attributes) {
                    return UserFavouriteTypeModel::where([
                        'sports_type'   => UserFavouriteTypeModel::SPORTS_TYPE_FOOTBALL,
                        'type'          => UserFavouriteTypeModel::LEAGUE_TYPE,
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
