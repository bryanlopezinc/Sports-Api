<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\DTO\Coach;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Contracts\Repositories\FetchCoachRepositoryInterface;
use Module\Football\Clients\ApiSports\V3\Response\CoachResponseJsonMapper;

final class FetchCoachHttpClient extends ApiSportsClient implements FetchCoachRepositoryInterface
{
    public function byId(CoachId $coachId): Coach
    {
        $response = $this->get('coachs', [
            'id'    => $coachId->toInt(),
        ])->json('response.0');

        return (new CoachResponseJsonMapper($response))->mapIntoDataTransferObject();
    }
}
