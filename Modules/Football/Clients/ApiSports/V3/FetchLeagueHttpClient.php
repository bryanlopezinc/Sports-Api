<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\DTO\League;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Collections\LeagueIdsCollection;
use Module\Football\Clients\ApiSports\V3\Requests\FetchLeagueByIdRequest;
use Module\Football\Contracts\Repositories\FetchLeagueRepositoryInterface;
use Module\Football\Clients\ApiSports\V3\Response\LeagueResponseJsonMapper;

final class FetchLeagueHttpClient extends ApiSportsClient implements FetchLeagueRepositoryInterface
{
    public function findByIdAndSeason(LeagueId $id, Season $season): League
    {
        $response = $this->get(new Request('leagues', [
            'season'  => $season->toInt(),
            'id'      => $id->toInt()
        ]));

        return $this->mapJsonResponseIntoLeagueDto($response, $season);
    }

    public function findManyById(LeagueIdsCollection $ids): LeaguesCollection
    {
        $requests = $ids->toLaravelCollection()->map(fn (LeagueId $id) => new FetchLeagueByIdRequest($id))->all();

        return collect($this->pool($requests))
            ->map(fn (Response $response): League => $this->mapJsonResponseIntoLeagueDto($response))
            ->pipe(fn (Collection $collection) => new LeaguesCollection($collection->all()));
    }

    public function mapJsonResponseIntoLeagueDto(Response $response, Season $season = null): League
    {
        return (new LeagueResponseJsonMapper($response->json('response.0')))->tooDataTransferObject($season);
    }
}
