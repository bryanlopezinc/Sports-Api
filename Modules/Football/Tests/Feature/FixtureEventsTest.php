<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureEventsRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureEventsResponse;

class FixtureEventsTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixtureEventsRoute(new FixtureId($id))
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureEventsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(400)->assertSuccessful();
    }
}
