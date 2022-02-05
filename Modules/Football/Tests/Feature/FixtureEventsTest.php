<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureEventsRoute;
use Module\Football\Routes\RouteName;
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

    public function test_will_throw_validation_error_when_required_fields_are_missing()
    {
        $this->getJson(route(RouteName::FIXTURE_EVENTS))->assertStatus(422)->assertJsonValidationErrors(['id']);
    }

    public function test_will_return_not_found_status_code_when_fixture_does_not_exists()
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(33)->assertNotFound();
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

    public function test_empty_fixture_events_http_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureEventsResponse::noContent())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(400)->assertSuccessful()->assertJsonCount(0, 'data.events');
    }
}
