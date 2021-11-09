<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\TeamFixtureStatistics;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Clients\ApiSports\V3\ApiSportsClient;
use Module\Football\Exceptions\Http\FixtureStatisticsNotAvailableHttpException;
use Module\Football\Contracts\Repositories\FetchFixtureStatisticsRepositoryInterface;

final class FetchFixtureStatisticsHttpClient extends ApiSportsClient implements FetchFixtureStatisticsRepositoryInterface
{
    public function fetchStats(FixtureId $id): FixtureStatistics
    {
        return $this->get('fixtures/statistics', ['fixture' => $id->toInt()])
            ->collect('response')
            ->whenEmpty(fn () => throw new FixtureStatisticsNotAvailableHttpException)
            ->map(function (array $data): TeamFixtureStatistics {
                return (new Response\FixtureStatisticsResponseJsonMapper($data))->toDataTransferObject();
            })
            ->pipe(fn(Collection $collection) => new FixtureStatistics($id, ...$collection->all()));
    }
}
