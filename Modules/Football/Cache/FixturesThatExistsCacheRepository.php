<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Services\FetchFixtureService;

/**
 * Since a fixture is only written and not deleted, This Repository will record fixture ids that have been
 * retrieved from data provider to Mitigate calls to the data provider just to check if a fixture exists.
 */
final class FixturesThatExistsCacheRepository
{
    public function __construct(private Repository $repository, private FetchFixtureService $service)
    {
    }

    private function record(FixtureId $id): void
    {
        $storage = $this->getStorage();

        $storage[$id->toInt()] = true;

        $this->repository->put($this->key(), $storage, now()->add(3));
    }

    public function exists(FixtureId $fixtureId): bool
    {
        if (isset($this->getStorage()[$fixtureId->toInt()])) {
            return true;
        }

        $result = $this->service->findMany(new FixtureIdsCollection([$fixtureId]));

        if ($result->isNotEmpty()) {
            $this->record($result->sole()->id());

            return true;
        }

        //Allow Provider to handle fixtures that dont't exist
        return false;
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
