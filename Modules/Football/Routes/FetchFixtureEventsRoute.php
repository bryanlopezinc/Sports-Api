<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\FixtureId;

final class FetchFixtureEventsRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private FixtureId $id)
    {
    }

    public function __toString()
    {
        return route(RouteName::FIXTURE_EVENTS, [
            'id' => $this->id->asHashedId()
        ]);
    }
}
