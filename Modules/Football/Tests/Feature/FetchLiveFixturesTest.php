<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLiveFixturesResponse;

class FetchLiveFixturesTest extends TestCase
{
    private function getTestResponse(): TestResponse
    {
        return $this->getJson(route(Name::FETCH_LIVE_FIXTURES));
    }

    /**
     * @test
     */
    public function FetchLiveFixtures_success_response(): void
    {
        Http::fakeSequence()->push(FetchLiveFixturesResponse::json());

        $this->withoutExceptionHandling()
            ->getTestResponse()
            ->assertSuccessful()
            ->assertHeader('max-age');
    }
}
