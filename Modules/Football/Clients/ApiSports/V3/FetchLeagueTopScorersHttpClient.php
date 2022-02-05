<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\LeagueTopScorer;
use Module\Football\Collections\LeagueTopScorersCollection;
use Module\Football\Clients\ApiSports\V3\Response\PlayerResponseJsonMapper;
use Module\Football\Contracts\Repositories\FetchLeagueTopScorersRepositoryInterface;

final class FetchLeagueTopScorersHttpClient extends ApiSportsClient implements FetchLeagueTopScorersRepositoryInterface
{
    public function topScorerers(LeagueId $leagueId, Season $season): LeagueTopScorersCollection
    {
        return $this->get('players/topscorers', [
            'league'    => $leagueId->toInt(),
            'season'    => $season->toInt()
        ])
            ->collect('response')
            ->map($this->mapTopScorerCallback())
            ->pipe(fn (Collection $collection) => new LeagueTopScorersCollection($collection->all()));
    }

    private function mapTopScorerCallback(): \Closure
    {
        return function (array $data) {
            return new LeagueTopScorer(
                (new PlayerResponseJsonMapper($data['player']))->toDataTransferObject(),
                Arr::get($data, 'statistics.0.goals.total')
            );
        };
    }
}
