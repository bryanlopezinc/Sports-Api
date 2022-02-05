<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\DTO\Player;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\Contracts\Repositories\FetchPlayerRepositoryInterface;
use Module\Football\Clients\ApiSports\V3\Response\PlayerResponseJsonMapper;

final class FetchPlayerHttpClient extends ApiSportsClient implements FetchPlayerRepositoryInterface
{
    public function findById(PlayerId $id): Player
    {
        $response = $this->get('players', [
            'id' => $id->toInt(),

            //since player info needed for this request is not affected by season parameter
            //and the current http client needs the season parameter to
            //return player info we can set this value to a fixed value
            //which is an available season for player profile and statistics (see https://www.api-football.com/documentation-v3#operation/get-players-seasons)
            'season' => 2020
        ])->json('response.0.player');

        return (new PlayerResponseJsonMapper($response))->toDataTransferObject();
    }
}
