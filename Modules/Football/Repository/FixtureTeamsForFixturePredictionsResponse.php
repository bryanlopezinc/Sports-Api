<?php

declare(strict_types=1);

namespace Module\Football\Repository;

use Module\Football\DTO\Fixture;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DTO\Builders\FixtureBuilder;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;

/**
 * cache of fixture teams for predictions response.
 *
 * Since its unlikely that fixture teams will ever change we can store
 * fixture teams aside for a longer period to avoid making a query to the
 * data provider when only fixture teams will always be needed.
 */
final class FixtureTeamsForFixturePredictionsResponse implements FetchFixtureRepositoryInterface
{
    public function __construct(private FetchFixtureRepositoryInterface $repository, private Repository $cache)
    {
    }

    public function FindFixtureById(FixtureId $id): Fixture
    {
        $key = 'fixtureTeamsForPredictions:' . $id->toInt();

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $fixture = $this->repository->FindFixtureById($id);

        $fixtureTeams = (new FixtureBuilder())
            ->setHomeTeam($fixture->getHomeTeam())
            ->setAwayTeam($fixture->getAwayTeam())
            //skip validation for Module\Football\Attributes\FixtureValidators\EnsureWinnerIdBelongsToFixtureTeams
            ->setWinnerId(null)
            ->build();

        $this->cache->put($key, $fixtureTeams, now()->addMonth());

        return $fixture;
    }
}
