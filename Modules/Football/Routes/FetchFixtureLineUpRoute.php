<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\FixtureId;

final class FetchFixtureLineUpRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private FixtureId $id)
    {
    }

    public function __toString()
    {
        return route(RouteName::FIXTURE_LINEUP, [
            'id' => $this->id->asHashedId()
        ]);
    }
}
