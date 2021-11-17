<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\ValueObjects\CoachId;
use Module\Football\ValueObjects\CoachCareer;
use Module\Football\Collections\CoachCareerHistory;
use Module\Football\Clients\ApiSports\V3\Response\CoachCareerJsonMapper;
use Module\Football\Contracts\Repositories\FetchCoachCareerHistoryRepositoryInterface;

final class FetchCoachCareerHttpClient extends ApiSportsClient implements FetchCoachCareerHistoryRepositoryInterface
{
    public function byId(CoachId $coachId): CoachCareerHistory
    {
        return $this->get('coachs', ['id' => $coachId->toInt()])
            ->collect('response.0.career')
            ->map(fn (array $career): CoachCareer => (new CoachCareerJsonMapper($career))->mapIntoCoachCareerObject())
            ->pipe(fn (Collection $collection) => new CoachCareerHistory($collection->all()));
    }
}
