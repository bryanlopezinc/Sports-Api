<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Service;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Factories\UserFactory;
use Module\User\Predictions\Football\FetchFixturePredictionsService;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;

class FetchFixturePredictionsServiceTest extends TestCase
{
    public function test_authUserHasPredictedFixture_will_return_false_when_user_is_not_logged_in(): void
    {
        $repository = $this->getMockBuilder(FetchFixturePredictionsRepositoryInterface::class)->getMock();

        $repository->expects($this->never())->method('userHasPredictedFixture');

        $this->swap(FetchFixturePredictionsRepositoryInterface::class, $repository);

        /** @var FetchFixturePredictionsService */
        $service = app(FetchFixturePredictionsService::class);

        $this->assertFalse($service->authUserHasPredictedFixture(new FixtureId(33)));
    }

    public function test_authUserHasPredictedFixture_will_query_repository_when_user_is_logged_in(): void
    {
        $repository = $this->getMockBuilder(FetchFixturePredictionsRepositoryInterface::class)->getMock();

        $repository->expects($this->once())->method('userHasPredictedFixture')->willReturn(true);

        $this->swap(FetchFixturePredictionsRepositoryInterface::class, $repository);

        Passport::actingAs(UserFactory::new()->create());

        /** @var FetchFixturePredictionsService */
        $service = app(FetchFixturePredictionsService::class);

        $this->assertTrue($service->authUserHasPredictedFixture(new FixtureId(33)));
    }
}
