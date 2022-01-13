<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Resources;

use Tests\TestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Module\Football\DTO\Fixture;
use Module\Football\Factories\FixtureFactory;
use Module\Football\Http\FetchFixtureResource\SetUserHasPredictionFixture;

class SetUserHasPredictionFixtureTest extends TestCase
{
    public function test_will_return_all_user_attributes_when_no_fields_are_requested(): void
    {
        $this->getTestReponse([])
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['has_predicted']
                ],
            ]);
    }

    public function test_will_return_user_has_predicted_attribute_when_requested(): void
    {
        $this->getTestReponse(['user.has_predicted'])
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['has_predicted']
                ],
            ]);
    }

    public function test_will_not_return_user_attributes_when_no_user_field_is_requested(): void
    {
        $this->getTestReponse(['status'])->assertJsonCount(0, 'data');
    }

    private function getTestReponse(array $filters): TestResponse
    {
        request()->merge(['filter' => implode(',', $filters)]);

        $resource = new SetUserHasPredictionFixture($this->getBaseResourceInstance());

        return new TestResponse(new Response(
            $resource->toResponse(request())->content()
        ));
    }

    private function getBaseResourceInstance(): JsonResource
    {
        $fixture = FixtureFactory::new()->toDto();

        return new class($fixture) extends JsonResource
        {
            public function __construct(Fixture $fixture)
            {
                parent::__construct($fixture);
            }

            public function toArray($request)
            {
                return [];
            }
        };
    }
}
