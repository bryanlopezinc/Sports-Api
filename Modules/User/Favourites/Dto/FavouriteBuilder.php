<?php

declare(strict_types=1);

namespace Module\User\Favourites\Dto;

use App\DTO\Builder;
use App\ValueObjects\ResourceId;
use Module\User\Favourites\Type;
use Module\User\ValueObjects\UserId;
use Module\User\Favourites\SportType;
use Module\User\Favourites\Models\FavouriteType;
use Module\User\Favourites\Models\Favourite as Model;

final class FavouriteBuilder extends Builder
{
    public static function fromModel(Model $model): self
    {
        return (new self)
            ->setId($model['id'])
            ->setType(match ($model['type']['type']) {
                FavouriteType::TEAM_TYPE   => Type::TEAM,
                FavouriteType::LEAGUE_TYPE => Type::LEAGUE,
            })
            ->setSportType(match ($model['type']['sports_type']) {
                FavouriteType::SPORTS_TYPE_FOOTBALL   => SportType::FOOTBALL,
            })
            ->setFavouriteId($model['favourite_id'])
            ->setUserId($model['user_id']);
    }

    public function setSportType(string $type): self
    {
        return $this->set('sportType', new SportType($type));
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
        return $this->set('type', new Type($type));
    }

    public function build(): Favourite
    {
        return new Favourite($this->toArray());
    }
}
