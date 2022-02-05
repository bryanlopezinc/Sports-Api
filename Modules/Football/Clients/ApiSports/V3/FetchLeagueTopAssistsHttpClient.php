<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\LeagueTopAssist;
use Module\Football\Collections\LeagueTopAssistsCollection;
use Module\Football\Clients\ApiSports\V3\Response\PlayerResponseJsonMapper;
use Module\Football\Contracts\Repositories\FetchLeagueTopAssistsRepositoryInterface;

final class FetchLeagueTopAssistsHttpClient extends ApiSportsClient implements FetchLeagueTopAssistsRepositoryInterface
{
    public function topAssists(LeagueId $leagueId, Season $season): LeagueTopAssistsCollection
    {
        return $this->get('players/topassists', [
            'league' => $leagueId->toInt(),
            'season' => $season->toInt()
        ])
            ->collect('response')
            ->map($this->mapCallback())
            ->pipe(fn (Collection $collection) => new LeagueTopAssistsCollection($collection->all()));
    }

    private function mapCallback(): \Closure
    {
        return function (array $data) {
            return new LeagueTopAssist(
                (new PlayerResponseJsonMapper($data['player']))->toDataTransferObject(),
                Arr::get($data, 'statistics.0.goals.assists')
            );
        };
    }
}
