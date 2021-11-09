<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use App\ValueObjects\Date;
use Module\Football\DTO\Fixture;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Clients\ApiSports\V3\Response\FixtureResponseJsonMapper;
use Module\Football\Contracts\Repositories\FetchLeagueFixturesByDateRepositoryInterface;

final class FetchLeagueFixturesByDateHttpClient extends ApiSportsClient implements FetchLeagueFixturesByDateRepositoryInterface
{
    public function findBy(LeagueId $leagueId, Season $season, Date $date): FixturesCollection
    {
        return $this->get('fixtures', [
                'date'      => $date->toCarbon()->toDateString(),
                'league'    => $leagueId->toInt(),
                'season'    => $season->toInt()
            ])
            ->collect('response')
            ->map(fn (array $response): Fixture => (new FixtureResponseJsonMapper($response))->toDataTransferObject())
            ->pipe(fn (Collection $collection) => new FixturesCollection($collection->all()));
    }
}
