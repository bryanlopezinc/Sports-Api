<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\CoachId;

final class FetchCoachRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private CoachId $id)
    {
    }

    public function __toString()
    {
        return route(Name::FETCH_COACH, [
            'id' => $this->id->asHashedId()
        ]);
    }
}
