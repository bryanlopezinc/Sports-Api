<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\FixtureId;

final class FetchFixtureStatisticsRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private FixtureId $id, private array $query = [])
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_FIXTURE_STATS, array_merge($this->query, [
            'id' => $this->id->asHashedId()
        ]));
    }
}
