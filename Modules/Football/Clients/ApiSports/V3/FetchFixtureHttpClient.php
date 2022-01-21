<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Http\Client\Response as ClientResponse;
use Module\Football\DTO\Fixture;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DTO\Builders\FixtureBuilder;
use Module\Football\Services\FetchLeagueService;
use Module\Football\Clients\ApiSports\V3\Response;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Collections\FixturesCollection;
use Module\Football\DetermineFixtureTimeToLiveInCache;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Contracts\Cache\FixturesEventsCacheInterface;
use Module\Football\Contracts\Cache\FixturesStatisticsCacheInterface;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;

final class FetchFixtureHttpClient extends ApiSportsClient implements FetchFixtureRepositoryInterface
{
    public function __construct(
        private FetchLeagueService $service,
        private FixturesEventsCacheInterface $fixturesEventsCache,
        private FixturesStatisticsCacheInterface $fixturesStatisticsCache,
    ) {
        parent::__construct();
    }

    public function FindFixtureById(FixtureId $id): Fixture
    {
        $response =  $this->get('fixtures', [
            'id' => $id->toInt(),
        ])->json('response.0');

        $fixture = (new Response\FixtureResponseJsonMapper($response))->toDataTransferObject();

        // fetch full league info to get data like coverage etc
        $league = $this->service->findByIdAndSeason($fixture->league()->getId(), new Season((new Response\Response($response))->get('league.season')));

        $fixture = FixtureBuilder::fromFixture($fixture)->setLeague($league)->build();

        $this->cacheFixtureStatistics($fixture, $response);
        $this->cacheFixtureEvents($fixture, $response);

        return $fixture;
    }

    public function findManyById(FixtureIdsCollection $fixtureIds): FixturesCollection
    {
        return $fixtureIds->toLaravelCollection()
            ->map(fn (FixtureId $id) => new Request('fixtures', ['id' => $id->toInt()]))
            ->pipe(fn (Collection $collection) => collect($this->pool($collection->all())))
            ->map(fn (ClientResponse $response) => (new Response\FixtureResponseJsonMapper($response->json('response.0')))->toDataTransferObject())
            ->pipe(fn (Collection $collection) => new FixturesCollection($collection->all()));
    }

    /**
     * @param array<string, mixed> $response
     */
    private function cacheFixtureEvents(Fixture $fixture, array $response): void
    {
        if (!isset($response['events'])) {
            return;
        }

        if (empty($response['events'])) {
            return;
        }

        $this->fixturesEventsCache->cache(
            $fixture->id(),
            (new Response\FixtureEventsResponseJsonMapper($response['events']))->toCollection(),
            (new DetermineFixtureTimeToLiveInCache)->for($fixture)
        );
    }

    /**
     * @param array<string, mixed> $response
     */
    private function cacheFixtureStatistics(Fixture $fixture, array $response): void
    {
        if (!isset($response['statistics'])) {
            return;
        }

        if (empty($response['statistics'])) {
            return;
        }

        $statistics = collect($response['statistics'])
            ->map(fn (array $statistic) => (new Response\FixtureStatisticsResponseJsonMapper($statistic))->toDataTransferObject())
            ->pipe(fn (Collection $collection) => new FixtureStatistics($fixture->id(), ...$collection->all()));

        $this->fixturesStatisticsCache->cache(
            $statistics,
            (new DetermineFixtureTimeToLiveInCache)->for($fixture)
        );
    }
}
