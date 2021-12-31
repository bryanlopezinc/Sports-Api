<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\PlayerTransferHistory;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\Clients\ApiSports\V3\Response\PlayerTransferHistoryJsonMapper;
use Module\Football\Contracts\Repositories\FetchPlayerTransferHistoryRepositoryInterface;

final class FetchPlayerTransferHistoryHttpClient extends ApiSportsClient implements FetchPlayerTransferHistoryRepositoryInterface
{
    public function forPlayer(PlayerId $id): PlayerTransferHistory
    {
        $response = $this->get('transfers', ['player' => $id->toInt()])->json('response.0');

        return (new PlayerTransferHistoryJsonMapper($response))->map();
    }
}
