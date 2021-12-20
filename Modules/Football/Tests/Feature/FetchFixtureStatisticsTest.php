<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureStatisticsRoute;
use Module\Football\Routes\Name;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureStatisticsResponse;

class FetchFixtureStatisticsTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixtureStatisticsRoute(new FixtureId($id))
        );
    }

    public function test_will_throw_validation_error_when_required_fields_are_missing()
    {
        $this->getJson(route(Name::FETCH_FIXTURE_STATS))->assertStatus(422)->assertJsonValidationErrors(['id']);
    }

    /**
     * @test
     */
    public function FetchFixtureStatistics_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()->getTestResponse(34)->assertSuccessful();
    }

    public function test_will_return_403_status_code_when_fixture_statistics_is_not_supported()
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::set($json, 'response.0.seasons.9.coverage.fixtures.statistics_fixtures', false); //use the same season year with fixture stub league season year
        Arr::set($json, 'response.0.seasons', [Arr::get($json, 'response.0.seasons.9')]);

        Http::fakeSequence() ->push(FetchFixtureResponse::json())->push(json_encode($json));

        $this->getTestResponse(34)->assertStatus(403);
    }
}
