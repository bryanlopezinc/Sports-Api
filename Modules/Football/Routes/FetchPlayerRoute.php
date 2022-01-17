<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\PlayerId;

final class FetchPlayerRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private PlayerId $id)
    {
    }

    public function __toString()
    {
        return route(RouteName::FIND_PLAYER, ['id' => $this->id->asHashedId()]);
    }
}
