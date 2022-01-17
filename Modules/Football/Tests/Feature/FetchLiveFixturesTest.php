<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLiveFixturesResponse;

class FetchLiveFixturesTest extends TestCase
{
    private function getTestResponse(array $query = []): TestResponse
    {
        return $this->getJson(route(RouteName::LIVE_FIXTURES, $query));
    }

    /**
     * @test
     */
    public function FetchLiveFixtures_success_response(): void
    {
        Http::fakeSequence()->push(FetchLiveFixturesResponse::json());

        $response = $this->withoutExceptionHandling()
            ->getTestResponse()
            ->assertSuccessful();

        $this->assertEquals(60, $response->baseResponse->headers->getCacheControlDirective('max-age'));
    }

    public function test_will_return_partial_resource_if_requested(): void
    {
        Http::fakeSequence()->push(FetchLiveFixturesResponse::json());

        $response = $this->getTestResponse(['filter' => 'score,minutes_elapsed'])->assertSuccessful();

        foreach ($response->json('data') as $data) {
            (new AssertableJsonString($data))
                ->assertCount(3, 'attributes')
                ->assertStructure([
                    'attributes' => [
                        'minutes_elapsed',
                        'score_is_available',
                        'score' => [
                            'home',
                            'away'
                        ]
                    ]
                ]);
        }
    }
}
