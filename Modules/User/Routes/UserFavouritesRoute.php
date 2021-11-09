<?php

declare(strict_types=1);

namespace Module\User\Routes;

use JsonSerializable;
use Module\User\ValueObjects\UserId;

final class UserFavouritesRoute implements JsonSerializable
{
    public function __construct(private UserId $userId)
    {
    }

    public function __toString()
    {
        return route(RouteName::FAVOURITES, [
            'id'   => $this->userId->toInt()
        ]);
    }

    public function jsonSerialize()
    {
        return (string) $this;
    }
}
