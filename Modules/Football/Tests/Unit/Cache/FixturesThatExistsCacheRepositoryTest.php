<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Module\Football\Cache\FixturesThatExistsCacheRepository;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;
use Module\Football\Factories\FixtureFactory;
use Module\Football\Services\FetchFixtureService;
use Module\Football\ValueObjects\FixtureId;

class FixturesThatExistsCacheRepositoryTest extends TestCase
{
    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject>
     */
    private function mocks(): array
    {
        return [
            $this->getMockBuilder(FetchFixtureRepositoryInterface::class)->getMock(),
            $this->getMockBuilder(Repository::class)->getMock()
        ];
    }

    public function test_will_return_false_and_not_record_fixture_id(): void
    {
        [$client, $cache] = $this->mocks();

        $client->expects($this->exactly(2))->method('findManyById')->willReturn(new FixturesCollection([]), new FixturesCollection([]));
        $cache->expects($this->never())->method('put');
        $cache->method('get')->willReturn([]);

        $this->swap(FetchFixtureRepositoryInterface::class, $client);

        $repository = new FixturesThatExistsCacheRepository($cache, app(FetchFixtureService::class));

        $this->assertFalse($repository->exists(new FixtureId(2020)));
        $this->assertFalse($repository->exists(new FixtureId(2020)));
    }

    public function test_will_return_true_and_record_fixture_id(): void
    {
        $client = $this->mocks()[0];

        $client->expects($this->once())->method('findManyById')->willReturn(new FixturesCollection([$fixture = FixtureFactory::new()->toDto()]));

        $this->swap(FetchFixtureRepositoryInterface::class, $client);

        $repository = new FixturesThatExistsCacheRepository(Cache::store(), app(FetchFixtureService::class));

        $this->assertTrue($repository->exists($fixture->id()));
        $this->assertTrue($repository->exists($fixture->id()));
        $this->assertTrue($repository->exists($fixture->id()));
    }
}
