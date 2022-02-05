<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Clients\ApiSports\V3\ApiSportsClient;
use Module\Football\DTO\FixtureStatistics as FixtureStatisticsDto;
use Module\Football\Contracts\Repositories\FetchFixtureStatisticsRepositoryInterface;

final class FetchFixtureStatisticsHttpClient extends ApiSportsClient implements FetchFixtureStatisticsRepositoryInterface
{
    public function fetchStats(FixtureId $id): FixtureStatistics
    {
        $statistcs =  $this->get('fixtures/statistics', ['fixture' => $id->toInt()])->collect('response');

        if ($statistcs->isEmpty()) {
            return new FixtureStatistics($id, new FixtureStatisticsDto([]), new FixtureStatisticsDto([]));
        }

        return $statistcs->map(new Response\FixtureStatisticsResponseJsonMapper())->pipe(fn (Collection $collection) => new FixtureStatistics($id, ...$collection->all()));
    }
}
