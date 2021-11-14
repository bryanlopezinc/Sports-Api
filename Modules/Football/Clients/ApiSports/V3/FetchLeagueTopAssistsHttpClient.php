<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\LeagueTopAssist;
use Module\Football\Collections\LeagueTopAssistsCollection;
use Module\Football\Clients\ApiSports\V3\Response\LeagueTopAssistJsonMapper;
use Module\Football\Exceptions\Http\LeagueTopAssistsNotAvailableHttpException;
use Module\Football\Contracts\Repositories\FetchLeagueTopAssistsRepositoryInterface;

final class FetchLeagueTopAssistsHttpClient extends ApiSportsClient implements FetchLeagueTopAssistsRepositoryInterface
{
    public function topAssists(LeagueId $leagueId, Season $season): LeagueTopAssistsCollection
    {
        $response = $this->get('players/topassists', [
            'league'    => $leagueId->toInt(),
            'season'    => $season->toInt()
        ]);

        if ($response->status() === 204) {
            throw new LeagueTopAssistsNotAvailableHttpException;
        }

        return $response
            ->collect('response')
            ->map(fn (array $data): LeagueTopAssist => (new LeagueTopAssistJsonMapper($data))->mapIntoLeagueTopAssist())
            ->pipe(fn (Collection $collection) => new LeagueTopAssistsCollection($collection->all()));
    }
}