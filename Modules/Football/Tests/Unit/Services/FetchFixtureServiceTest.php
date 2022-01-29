<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Services;

use Illuminate\Support\Facades\Http;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Collections\FixturesCollection;
use Tests\TestCase;
use Module\Football\DTO\Fixture;
use Module\Football\Factories\FixtureFactory;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Contracts\Cache\FixturesCacheInterface;
use Module\Football\Contracts\Repositories\FetchFixtureRepositoryInterface;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\ValueObjects\FixtureId;

class FetchFixtureServiceTest extends TestCase
{
    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject>
     */
    private function getMocks()
    {
        return [
            $this->getMockBuilder(FixturesCacheInterface::class)->getMock(),
            $this->getMockBuilder(FetchFixtureRepositoryInterface::class)->getMock()
        ];
    }

    public function test_will_not_query_repository_when_all_fixtures_exists_in_cache(): void
    {
        [$cache, $repository] = $this->getMocks();

        $repository->expects($this->never())->method('findManyById');

        $cache->expects($this->once())->method('getMany')->willReturn(FixtureFactory::new()->count(5)->toCollection());

        $this->assertCount(
            5,
            $this->getServiceInstance($cache, $repository)->findMany(FixtureFactory::new()->count(5)->toCollection()->ids())
        );
    }

    public function test_will_query_repository_for_only_items_that_dont_exists_in_cache(): void
    {
        $fixtures = FixtureFactory::new()->count(5)->toCollection();

        /** @var Fixture */
        $last = $fixtures->toLaravelCollection()->last();

        [$cache, $repository] = $this->getMocks();

        $cache->expects($this->once())
            ->method('getMany')
            ->willReturn(new FixturesCollection($fixtures->toLaravelCollection()->take(4)->all()));

        $repository
            ->expects($this->once())
            ->method('findManyById')
            ->willReturnCallback(function (FixtureIdsCollection $arg) use ($last, $fixtures) {
                $this->assertTrue($last->id()->equals($arg->toLaravelCollection()->sole()));

                return new FixturesCollection([$fixtures->toLaravelCollection()->last()]);
            });

        $this->assertCount(5, $this->getServiceInstance($cache, $repository)->findMany($fixtures->ids()));
    }

    public function test_will_query_client_once_to_check_if_fixture_exists(): void
    {
        $cache = $this->getMocks()[0];
        $cache->expects($this->exactly(7))->method('has')->willReturn(false);

        $this->swap(FixturesCacheInterface::class, $cache);

        /** @var FetchFixtureService */
        $service = app(FetchFixtureService::class);

        Http::fake(fn () => Http::response(FetchFixtureResponse::json()));

        $this->assertTrue($service->exists(new FixtureId(215662)));
        $this->assertTrue($service->exists(new FixtureId(215662)));
        $this->assertTrue($service->exists(new FixtureId(215662)));
        $this->assertTrue($service->exists(new FixtureId(215662)));
        $this->assertTrue($service->exists(new FixtureId(215662)));
        $this->assertTrue($service->exists(new FixtureId(215662)));
        $this->assertTrue($service->exists(new FixtureId(215662)));

        Http::assertSentCount(1);
    }

    private function getServiceInstance($cache, $repository): FetchFixtureService
    {
        $this->instance(FixturesCacheInterface::class, $cache);
        $this->instance(FetchFixtureRepositoryInterface::class, $repository);

        return app(FetchFixtureService::class);
    }
}
