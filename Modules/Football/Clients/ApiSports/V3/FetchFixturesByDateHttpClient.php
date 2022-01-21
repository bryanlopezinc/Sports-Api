<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use App\ValueObjects\Date;
use Illuminate\Support\LazyCollection;
use Module\Football\LeagueFixturesGroup;
use Module\Football\Contracts\Repositories\FetchFixturesByDateRepositoryInterface;

final class FetchFixturesByDateHttpClient extends ApiSportsClient implements FetchFixturesByDateRepositoryInterface
{
    /**
     * @return array<LeagueFixturesGroup>
     */
    public function asGroup(Date $date): array
    {
        return $this->groupByLegueFixturesCount($this->fetchFixturesByDate($date));
    }

    public function fetchFixturesByDate(Date $date): LazyCollection
    {
        return new LazyCollection(function () use ($date) {
            yield from $this->get('fixtures', ['date' => $date->toCarbon()->toDateString()])->collect('response');
        });
    }

    /**
     * @return array<LeagueFixturesGroup>
     */
    private function groupByLegueFixturesCount(LazyCollection $collection): array
    {
        $jsonMapper = new Response\FixtureResponseJsonMapper([]);

        /** @var array<int, LeagueFixturesGroup> */
        $leaguesFixturesGroups = [];

        /** @var array $league */
        foreach ($collection as  ['league' => $league]) {
            $id = $league['id'];

            //If league id is not already set in array
            //this is first league fixture being encountered
            //so the fixtures count is one.
            if (!isset($leaguesFixturesGroups[$id])) {
                $leaguesFixturesGroups[$id] = new LeagueFixturesGroup($jsonMapper->mapLeagueResponseIntoDto($league), 1);

                continue;
            }

            $previousLeagueGroup = $leaguesFixturesGroups[$id];

            //A new league fixture has being found, overwrite previous
            //league group with incremented league fixtures count.
            $leaguesFixturesGroups[$id] = new LeagueFixturesGroup($previousLeagueGroup->league(), $previousLeagueGroup->fixturesCount() + 1);
        }

        return $leaguesFixturesGroups;
    }
}
