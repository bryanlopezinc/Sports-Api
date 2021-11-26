<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\DTO\PlayerStatistics;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixturePlayersStatisticsCollection;
use Module\Football\Contracts\Repositories\FetchPlayersStatisticsRepositoryInterface;

final class FetchPlayersStatisticsHttpClient extends ApiSportsClient implements FetchPlayersStatisticsRepositoryInterface
{
    public function fetchStatistics(FixtureId $id): FixturePlayersStatisticsCollection
    {
        return $this->get('fixtures/players', ['fixture' => $id->toInt()])
            ->collect('response')
            ->map(fn (array $data): PlayerStatistics => (new Response\PlayerStatisticResponseJsonMapper($data))->toDataTransferObject())
            ->pipe(fn (Collection $collection) => new FixturePlayersStatisticsCollection($collection->all()));
    }
}
