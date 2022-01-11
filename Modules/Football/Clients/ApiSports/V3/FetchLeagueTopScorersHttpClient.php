<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\LeagueTopScorer;
use Module\Football\Collections\LeagueTopScorersCollection;
use Module\Football\Clients\ApiSports\V3\Response\LeagueTopScorerJsonMapper;
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
            ->map(fn (array $playerData): LeagueTopScorer => (new LeagueTopScorerJsonMapper($playerData))->mapIntoLeagueScorerObject())
            ->pipe(fn (Collection $collection) => new LeagueTopScorersCollection($collection->all()));
    }
}
