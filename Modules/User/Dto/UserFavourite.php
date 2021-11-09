<?php

declare(strict_types=1);

namespace Module\User\Dto;

use App\DTO\DataTransferObject;
use App\ValueObjects\ResourceId;
use Module\User\ValueObjects\UserId;
use Module\User\ValueObjects\UserFavouriteType;
use Module\User\ValueObjects\UserFavouriteSportsType;

final class UserFavourite extends DataTransferObject
{
    protected ResourceId $id;
    protected UserId $userId;
    protected UserFavouriteType $type;
    protected UserFavouriteSportsType $sportsType;
    protected ResourceId $favouriteId;

    public function sportsType(): UserFavouriteSportsType
    {
        return $this->sportsType;
    }

    public function favouriteId(): ResourceId
    {
        return $this->favouriteId;
    }

    public function getId(): ResourceId
    {
        return $this->id;
    }

    public function getType(): UserFavouriteType
    {
        return $this->type;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
