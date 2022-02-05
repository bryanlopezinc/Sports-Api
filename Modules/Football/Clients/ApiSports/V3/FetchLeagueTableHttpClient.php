<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\DTO\League;
use Illuminate\Support\Collection;
use Module\Football\DTO\LeagueStanding;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Cache\LeaguesCacheRepository;
use Module\Football\Collections\LeagueTable;
use Module\Football\Services\CacheLeagueService;
use Module\Football\DTO\Builders\LeagueStandingBuilder as Builder;
use Module\Football\Contracts\Repositories\FetchLeagueStandingRepositoryInterface;
use Module\Football\Clients\ApiSports\V3\Response\LeagueStandingResponseJsonMapper;

final class FetchLeagueTableHttpClient extends ApiSportsClient implements FetchLeagueStandingRepositoryInterface
{
    public function __construct(private CacheLeagueService $service, private LeaguesCacheRepository $cache)
    {
        parent::__construct();
    }

    public function getLeagueTable(LeagueId $leagueId, Season $season): LeagueTable
    {
        $requests = [];

        $requests['standings'] = new ApiSportsRequest('standings', [
            'league' => $leagueId->toInt(),
            'season' => $season->toInt()
        ]);

        if ($this->shouldMakeHttpRequestFor($leagueId, $season)) {
            $requests['league'] = ApiSportsRequest::findLeagueRequest($leagueId, ['season' => $season->toInt()]);
        }

        $response = $this->pool($requests);

        $league = $this->getLeagueFromResponse($response, $leagueId, $season);

        return $response['standings']
            ->collect('response.0.league.standings.0')
            ->map(new LeagueStandingResponseJsonMapper)
            ->map(fn (LeagueStanding $standing): LeagueStanding => Builder::fromStanding($standing)->setLeague($league)->build())
            ->pipe(fn (Collection $collection) => new LeagueTable($collection->all()));
    }

    private function getLeagueFromResponse(array $response, LeagueId $leagueId, Season $season): League
    {
        if (!isset($response['league'])) {
            //did not make http request
            return $this->cache->findByIdAndSeason($leagueId, $season);
        }

        $this->service->cacheLeague(
            $league = (new FetchLeagueHttpClient())->mapJsonResponseIntoLeagueDto($response['league'])
        );

        return $league;
    }

    private function shouldMakeHttpRequestFor(LeagueId $leagueId, Season $season): bool
    {
        try {
            $this->cache->findByIdAndSeason($leagueId, $season);

            return false;
        } catch (ItemNotInCacheException) {
            return true;
        }
    }
}
