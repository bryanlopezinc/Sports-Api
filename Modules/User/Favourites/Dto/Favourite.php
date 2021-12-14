<?php

declare(strict_types=1);

namespace Module\User\Favourites\Dto;

use App\DTO\DataTransferObject;
use App\ValueObjects\ResourceId;
use Module\User\Favourites\Type;
use Module\User\ValueObjects\UserId;
use Module\User\Favourites\SportType;

final class Favourite extends DataTransferObject
{
    protected ResourceId $id;
    protected UserId $userId;
    protected Type $type;
    protected SportType $sportType;
    protected ResourceId $favouriteId;

    public function sportType(): SportType
    {
        return $this->sportType;
    }

    public function favouriteId(): ResourceId
    {
        return $this->favouriteId;
    }

    public function getId(): ResourceId
    {
        return $this->id;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
