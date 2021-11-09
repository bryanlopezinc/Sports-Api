<?php

declare(strict_types=1);

namespace Module\User\Repository;

use App\Utils\PaginationData;
use Illuminate\Support\Collection;
use Module\User\Dto\UserFavourite;
use Module\User\ValueObjects\UserId;
use Module\User\PaginatedUserFavouriteTypes;
use Module\User\Models\UserFavourite as Model;
use Module\User\ValueObjects\UserFavouriteType;
use Module\User\Dto\Builders\UserFavouriteBuilder;
use Module\User\ValueObjects\UserFavouriteSportsType;
use Module\User\Collections\UserFavouriteTypesCollection;
use Module\User\Contracts\CreateUserFavouriteRepositoryInterface;
use Module\User\Contracts\FetchUserFavouritesRepositoryInterface;
use Module\User\Models\UserFavouriteCount;
use Module\User\Models\UserFavouriteType as UserFavouriteTypeModel;

final class UserFavouritesRepository implements CreateUserFavouriteRepositoryInterface, FetchUserFavouritesRepositoryInterface
{
    private const FAVOURITE_TYPE_MAP = [
        UserFavouriteType::TEAM   => UserFavouriteTypeModel::TEAM_TYPE,
        UserFavouriteType::LEAGUE => UserFavouriteTypeModel::LEAGUE_TYPE,
    ];

    private const SPORTS_TYPE_MAP = [
        UserFavouriteSportsType::FOOTBALL => UserFavouriteTypeModel::SPORTS_TYPE_FOOTBALL,
    ];

    public function create(UserFavourite $favourite): bool
    {
        Model::create([
            'user_id'       => $favourite->getUserId()->toInt(),
            'type_id'       => $this->getTypeIdFrom($favourite),
            'favourite_id'  => $favourite->favouriteId()->toInt()
        ]);

        $favouritesCount = UserFavouriteCount::where('user_id', $favourite->getUserId()->toInt())->first();

        if ($favouritesCount === null) {
            UserFavouriteCount::create([
                'user_id'   => $favourite->getUserId()->toInt(),
                'count'     => 1
            ]);

            return true;
        }

        $favouritesCount->increment('count');

        return true;
    }

    private function getTypeIdFrom(UserFavourite $userFavourite): int
    {
        return UserFavouriteTypeModel::where([
            'type'        => self::FAVOURITE_TYPE_MAP[$userFavourite->getType()->getType()],
            'sports_type' => self::SPORTS_TYPE_MAP[$userFavourite->sportsType()->getType()],
        ])->first()->id;
    }

    public function exists(UserFavourite $favourite): bool
    {
        $result =  Model::where([
            'user_id'      => $favourite->getUserId()->toInt(),
            'type_id'      => $this->getTypeIdFrom($favourite),
            'favourite_id' => $favourite->favouriteId()->toInt()
        ])->get();

        return $result->isNotEmpty();
    }

    public function getFavourites(UserId $userId, PaginationData $pagination): PaginatedUserFavouriteTypes
    {
        /** @var \Illuminate\Pagination\Paginator */
        $result = Model::where('user_id', $userId->toInt())
            ->with('type')
            ->simplePaginate($pagination->getPerPage(), page: $pagination->getPage());

        return new PaginatedUserFavouriteTypes($this->mapFavouritesToDto($result->items()), $result);
    }

    /**
     * @param array<Model> $result
     */
    private function mapFavouritesToDto(array $result): UserFavouriteTypesCollection
    {
        return collect($result)
            ->map(fn (Model $model) => UserFavouriteBuilder::fromModel($model)->build())
            ->pipe(fn (Collection $collection) => new UserFavouriteTypesCollection($collection->all()));
    }
}
