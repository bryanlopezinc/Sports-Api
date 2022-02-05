<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\Clients\ApiSports\V3\Response\PlayerResponseJsonMapper;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Contracts\Repositories\FetchTeamSquadRepositoryInterface;
use Module\Football\ValueObjects\PlayerPosition;

final class FetchTeamSquadHttpClient extends ApiSportsClient implements FetchTeamSquadRepositoryInterface
{
    public function teamSquad(TeamId $teamId): PlayersCollection
    {
        return $this->get('players/squads', ['team' => (string) $teamId->toInt()])
            ->collect('response.0.players')
            ->map($this->mapPlayerDataIntoPlayerDto())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }

    private function mapPlayerDataIntoPlayerDto(): \Closure
    {
        return function (array $data) {
            $playerPositionMap = [
                'Goalkeeper'  => PlayerPosition::GOALIE,
                'Defender'    => PlayerPosition::DEFENDER,
                'Midfielder'  => PlayerPosition::MIDFIELDER,
                'Attacker'    => PlayerPosition::ATTACKER
            ];

            return (new PlayerResponseJsonMapper($data, $playerPositionMap))->toDataTransferObject();
        };
    }
}
