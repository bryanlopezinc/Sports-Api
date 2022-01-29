<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Module\Football\Cache\FixturesThatExistsCacheRepository;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;
use Module\Football\Factories\FixtureFactory;

class FixturesThatExistsCacheRepositoryTest extends TestCase
{
    public function test_will_cache_fixtures_that_exists(): void
    {
        $client = $this->getMockBuilder(FetchFixtureRepositoryInterface::class)->getMock();

        $client->expects($this->once())->method('findManyById')->willReturn($fixtures = FixtureFactory::new()->toCollection());
        $client->expects($this->once())->method('FindFixtureById')->willReturn($fixture = FixtureFactory::new()->toDto());

        $this->swap(FetchFixtureRepositoryInterface::class, $client);

        $repository = new FixturesThatExistsCacheRepository(Cache::store(), $client);

        $repository->findManyById($fixtures->ids());
        $repository->FindFixtureById($fixture->id());

        $this->assertTrue($repository->exists($fixture->id()));
        $this->assertTrue($repository->exists($fixture->id()));

        $this->assertTrue($repository->exists($fixtures->ids()->toLaravelCollection()->first()));
        $this->assertTrue($repository->exists($fixtures->ids()->toLaravelCollection()->first()));

        $this->assertTrue($repository->exists($fixtures->ids()->toLaravelCollection()->last()));
        $this->assertTrue($repository->exists($fixtures->ids()->toLaravelCollection()->last()));
    }
}
