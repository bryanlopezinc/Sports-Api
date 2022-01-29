<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;

class FetchFixturePredictionsTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(
            route(RouteName::FIXTURE_PREDICTIONS, ['id' => $this->hashId($id)])
        );
    }

    public function test_will_return_not_found_status_code_when_fixture_does_not_exists()
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(33)->assertNotFound();
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()->push(FetchFixtureResponse::json())->push(FetchLeagueResponse::json());

        $this->getTestResponse(215662)->assertSuccessful();
        $this->getTestResponse(215662)->assertSuccessful();
    }
}
