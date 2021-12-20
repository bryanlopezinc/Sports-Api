<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\FixtureId;

final class FetchFixtureRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private FixtureId $id, private array $query = [])
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_FIXTURE, array_merge([
            'id' => $this->id->asHashedId()
        ], $this->query));
    }
}
