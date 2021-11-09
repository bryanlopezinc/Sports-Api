<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\FixtureId;

final class FetchFixtureRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private FixtureId $id)
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_FIXTURE, [
            'id'    => $this->id->toInt()
        ]);
    }
}
