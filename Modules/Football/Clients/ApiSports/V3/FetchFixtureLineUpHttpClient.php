<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\FixtureLineUp;
use Illuminate\Http\Client\Response;
use Module\Football\TeamMissingPlayer;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DTO\Builders\TeamLineUpBuilder;
use Module\Football\Collections\TeamMissingPlayersCollection;
use Module\Football\Exceptions\Http\FixtureLineUpNotAvailableHttpException;
use Module\Football\Clients\ApiSports\V3\Response\TeamLineUpResponseJsonMapper;
use Module\Football\Clients\ApiSports\V3\Response\FixtureMissingPlayerJsonMapper;
use Module\Football\Contracts\Repositories\FetchFixtureLineUpRepositoryInterface;

final class FetchFixtureLineUpHttpClient extends ApiSportsClient implements FetchFixtureLineUpRepositoryInterface
{
    public function fetchLineUp(FixtureId $id): FixtureLineUp
    {
        $requests = [
            'lineUps'           => new Request('fixtures/lineups', ['fixture'  => $id->toInt()]),
            'missingPlayers'    => new Request('injuries', ['fixture'  => $id->toInt()]),
        ];

        $response = $this->pool($requests);

        $missingPlayers = $response['missingPlayers']->json('response');
        $fixtureLineUp = [];

        foreach ($this->getLineUpsDataFrom($response['lineUps']) as $data) {
            $builder = $this->setMissingPlayersForTeamLineUp($data, $missingPlayers);

            $fixtureLineUp[] = (new TeamLineUpResponseJsonMapper($data, teamLineUpBuilder: $builder))->toDataTransferObject();
        }

        return new FixtureLineUp(...$fixtureLineUp);
    }

    private function getLineUpsDataFrom(Response $response): array
    {
        $data = $response->collect('response');

        if ($data->isEmpty()) {
            throw new FixtureLineUpNotAvailableHttpException;
        }

        return $data->all();
    }

    private function setMissingPlayersForTeamLineUp(array $teamLineUp, array $missingPlayers): TeamLineUpBuilder
    {
        $builder = new TeamLineUpBuilder();

        if (empty($missingPlayers)) {
            return $builder->setMissingPlayers(new TeamMissingPlayersCollection([]));
        }

        $teamMissingPlayers = collect($missingPlayers)
            ->filter(fn (array $data) => $data['team']['id'] === $teamLineUp['team']['id'])
            ->map(fn (array $data): TeamMissingPlayer => (new FixtureMissingPlayerJsonMapper($data))->transformToFixtureMissingPlayer())
            ->pipe(fn (Collection $collection) => new TeamMissingPlayersCollection($collection->all()));

        return $builder->setMissingPlayers($teamMissingPlayers);
    }
}
