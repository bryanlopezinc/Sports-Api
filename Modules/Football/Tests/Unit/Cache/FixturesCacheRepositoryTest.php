<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Cache;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ItemNotInCacheException;
use Module\Football\Factories\FixtureFactory;
use Module\Football\Cache\FixturesCacheRepository;

class FixturesCacheRepositoryTest extends TestCase
{
    private FixturesCacheRepository $repository;

    protected function setUp():void
    {
        parent::setUp();

        $this->repository = new FixturesCacheRepository(Cache::store());
    }

    /**
     * @test
     */
    public function throws_exception_when_item_is_not_in_cache(): void
    {
        $this->expectException(ItemNotInCacheException::class);

        $this->repository->get(FixtureFactory::new()->toDto()->id());
    }

    /**
     * @test
     */
    public function returns_fixture_when_fixture_exists(): void
    {
        $fixture = FixtureFactory::new()->toDto();

        $this->repository->cache($fixture, TimeToLive::seconds(10));

        $this->assertEquals($this->repository->get($fixture->id()), $fixture);
    }

    /**
     * @test
     */
    public function return_true_when_fixture_exists(): void
    {
        $fixture = FixtureFactory::new()->toDto();

        $this->repository->cache($fixture, TimeToLive::seconds(10));

        $this->assertTrue($this->repository->has($fixture->id()));
    }

    /**
     * @test
     */
    public function return_false_when_fixture_does_not_exists(): void
    {
        $fixture = FixtureFactory::new()->toDto();

        $this->assertFalse($this->repository->has($fixture->id()));
    }
}
