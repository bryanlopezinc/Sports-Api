<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use Module\Football\ValueObjects\CoachId;

final class FetchCoachCareerHistoryRoute implements JsonSerializable
{
    use SerializeRoute;

    public function __construct(private CoachId $id)
    {
    }

    public function __toString()
    {
        return route(RouteName::COACH_CAREER_HISTORY, [
            'id' => $this->id->asHashedId()
        ]);
    }
}
