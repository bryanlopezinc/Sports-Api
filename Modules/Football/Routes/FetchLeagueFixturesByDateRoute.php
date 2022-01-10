<?php

declare(strict_types=1);

namespace Module\Football\Routes;

use JsonSerializable;
use App\ValueObjects\Date;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;

final class FetchLeagueFixturesByDateRoute implements JsonSerializable
{
    use SerializeRoute;

    private array $query = [];

    public function __construct(private LeagueId $leagueId, private Season $season, private Date $date)
    {
    }

    public function query(array $query): self
    {
        $this->query = array_merge($query, $this->query);

        return $this;
    }

    public function __toString()
    {
        return route(Name::FETCH_LEAGUE_FIXTURE_BY_DATE, [
            ...$this->query,
            'league_id'    => $this->leagueId->asHashedId(),
            'season'       => $this->season->toInt(),
            'date'         => $this->date->toCarbon()->toDateString()
        ]);
    }
}
