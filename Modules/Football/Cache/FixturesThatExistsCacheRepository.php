<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;
use Module\Football\DTO\Fixture;

/**
 * This class serves as a client for
 * @see \Module\Football\Services\FetchFixtureService
 * Since a fixture is only written and not deleted, This Repository will record fixture ids that have been
 * retrieved from data provider to Mitigate calls to the data provider just to check if a fixture exists with the 'Exists' method.
 * The FixturesCache can still acheieve the same purpose of checking if fixture exists
 *  but this will not be effective when a fixture is in progress because live fixtures are only cached for a short period of time.
 */
final class FixturesThatExistsCacheRepository implements FetchFixtureRepositoryInterface
{
    public function __construct(private Repository $repository, private FetchFixtureRepositoryInterface $client)
    {
    }

    public function FindFixtureById(FixtureId $id): Fixture
    {
        $fixture = $this->client->FindFixtureById($id);

        $this->record($fixture->id());

        return $fixture;
    }

    public function findManyById(FixtureIdsCollection $fixtureIds): FixturesCollection
    {
        $fixtures = $this->client->findManyById($fixtureIds);

        $this->record($fixtures->ids());

        return $fixtures;
    }

    private function record(FixtureId|FixtureIdsCollection $ids): void
    {
        $storage = $this->getStorage();

        $ids = $ids instanceof FixtureId ? new FixtureIdsCollection([$ids]) : $ids;

        foreach ($ids->toIntArray() as $id) {
            $storage[$id] = true;
        }

        $this->repository->put($this->key(), $storage, now()->addWeek(3));
    }

    public function exists(FixtureId $fixtureId): bool
    {
        if (isset($this->getStorage()[$fixtureId->toInt()])) {
            return true;
        }

        $exists = $this->client->exists($fixtureId);

        if ($exists) {
            $this->record($fixtureId);
        }

        //Allow Provider to handle fixtures that dont't exist
        return $exists;
    }

    private function getStorage(): array
    {
        return $this->repository->get($this->key(), []);
    }

    private function key(): string
    {
        return (string) new CachePrefix($this);
    }
}
