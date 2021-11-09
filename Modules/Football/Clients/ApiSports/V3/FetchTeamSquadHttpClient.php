<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\DTO\Player;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\PlayersCollection;
use Module\Football\Contracts\Repositories\FetchTeamSquadRepositoryInterface;
use Module\Football\Clients\ApiSports\V3\Response\TeamSquadJsonResponseMapper;

final class FetchTeamSquadHttpClient extends ApiSportsClient implements FetchTeamSquadRepositoryInterface
{
    public function teamSquad(TeamId $teamId): PlayersCollection
    {
        return $this->get('players/squads', ['team' => (string) $teamId->toInt()])
            ->collect('response.0.players')
            ->map(fn (array $playerData): Player => (new TeamSquadJsonResponseMapper($playerData))->tooDataTransferObject())
            ->pipe(fn (Collection $collection) => new PlayersCollection($collection->all()));
    }
}
