<?php

declare(strict_types=1);

namespace Module\User\Dto\Builders;

use App\DTO\Builder;
use App\ValueObjects\ResourceId;
use Module\User\Dto\UserFavourite;
use Module\User\ValueObjects\UserId;
use Module\User\Models\UserFavourite as Model;
use Module\User\ValueObjects\UserFavouriteType;
use Module\User\ValueObjects\UserFavouriteSportsType;
use Module\User\Models\UserFavouriteType as FavouriteTypeModel;

final class UserFavouriteBuilder extends Builder
{
    public static function fromModel(Model $model): self
    {
        return (new self)
            ->setId($model['id'])

            ->setType(match ($model['type']['type']) {
                FavouriteTypeModel::TEAM_TYPE   => UserFavouriteType::TEAM,
                FavouriteTypeModel::LEAGUE_TYPE => UserFavouriteType::LEAGUE,
            })

            ->setSportsType(match ($model['type']['sports_type']) {
                FavouriteTypeModel::SPORTS_TYPE_FOOTBALL   => UserFavouriteSportsType::FOOTBALL,
            })

            ->setFavouriteId($model['favourite_id'])
            ->setUserId($model['user_id']);
    }

    public function setSportsType(string $type): self
    {
        return $this->set('sportsType', new UserFavouriteSportsType($type));
    }

    public function setUserId(int $userId): self
    {
        return $this->set('userId', new UserId($userId));
    }

    public function setFavouriteId(int $id): self
    {
        return $this->set('favouriteId', new ResourceId($id));
    }

    public function setId(int $id): self
    {
        return $this->set('id', new ResourceId($id));
    }

    public function setType(string $type): self
    {
        return $this->set('type', new UserFavouriteType($type));
    }

    public function build(): UserFavourite
    {
        return new UserFavourite($this->toArray());
    }
}
