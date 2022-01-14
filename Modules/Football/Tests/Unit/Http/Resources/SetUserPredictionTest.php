<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Resources;

use Tests\TestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\Passport;
use Module\Football\DTO\Fixture;
use Module\Football\Factories\FixtureFactory;
use Module\Football\Http\FetchFixtureResource\SetUserPrediction;
use Module\Football\Http\Resources\FixtureJsonResourceInterface;
use Module\User\Factories\UserFactory;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;
use Module\User\Predictions\Football\Prediction;

class SetUserPredictionTest extends TestCase
{
    public function test_will_return_user_prediction_attribute_when_no_fields_are_requested(): void
    {
        $repository = $this->getMockBuilder(FetchFixturePredictionsRepositoryInterface::class)->getMock();

        $repository->method('userHasPredictedFixture')->willReturn(true);
        $repository->method('fetchUserPrediction')->willReturn(new Prediction(Prediction::AWAY_WIN));
        Passport::actingAs(UserFactory::new()->create());

        $this->swap(FetchFixturePredictionsRepositoryInterface::class, $repository);

        $this->getTestReponse([])
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['prediction']
                ],
            ]);
    }

    public function test_will_return_user_prediction_attribute_when_requested(): void
    {
        $repository = $this->getMockBuilder(FetchFixturePredictionsRepositoryInterface::class)->getMock();

        $repository->method('userHasPredictedFixture')->willReturn(true);
        $repository->method('fetchUserPrediction')->willReturn(new Prediction(Prediction::AWAY_WIN));
        Passport::actingAs(UserFactory::new()->create());

        $this->swap(FetchFixturePredictionsRepositoryInterface::class, $repository);

        $this->getTestReponse(['user.prediction'])
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['prediction']
                ],
            ]);
    }

    public function test_will_not_return_user_prediction_attribute_when_user_prediction_is_not_requested(): void
    {
        $this->getTestReponse(['status'])->assertJsonCount(0, 'data');
    }

    public function test_will_not_return_user_prediction_attribute_when_user_has_not_predicted_fixture(): void
    {
        $repository = $this->getMockBuilder(FetchFixturePredictionsRepositoryInterface::class)->getMock();

        $repository->method('userHasPredictedFixture')->willReturn(false);
        Passport::actingAs(UserFactory::new()->create());

        $this->swap(FetchFixturePredictionsRepositoryInterface::class, $repository);

        $this->getTestReponse([])->assertJsonCount(0, 'data');
    }

    private function getTestReponse(array $filters): TestResponse
    {
        request()->merge(['filter' => implode(',', $filters)]);

        $resource = new SetUserPrediction($this->getBaseResourceInstance());

        return new TestResponse(new Response(
            $resource->toResponse(request())->content()
        ));
    }

    private function getBaseResourceInstance(): JsonResource&FixtureJsonResourceInterface
    {
        $fixture = FixtureFactory::new()->toDto();

        return new class($fixture) extends JsonResource implements FixtureJsonResourceInterface
        {
            public function __construct(private Fixture $fixture)
            {
                parent::__construct($fixture);
            }

            public function toArray($request)
            {
                return [];
            }

            public function getFixture(): Fixture
            {
                return $this->fixture;
            }
        };
    }
}
