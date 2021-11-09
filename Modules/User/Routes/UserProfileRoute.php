<?php

declare(strict_types=1);

namespace Module\User\Routes;

use JsonSerializable;
use Module\User\ValueObjects\UserId;

final class UserProfileRoute implements JsonSerializable
{
    public function __construct(private UserId $userId)
    {
    }

    public function __toString()
    {
        if (request()->routeIs(RouteName::AUTH_USER_PROFILE)) {
            return route(RouteName::AUTH_USER_PROFILE);
        }

        return route(RouteName::PROFILE, [
            'id'   => $this->userId->toInt()
        ]);
    }

    public function jsonSerialize()
    {
        return (string) $this;
    }
}
