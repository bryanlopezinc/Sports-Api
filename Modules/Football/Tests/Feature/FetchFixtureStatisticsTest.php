<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureStatisticsRoute;
use Module\Football\Routes\RouteName;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureStatisticsResponse;

class FetchFixtureStatisticsTest extends TestCase
{
    private function getTestResponse(int $id, array $query = []): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixtureStatisticsRoute(new FixtureId($id), $query)
        );
    }

    public function test_will_throw_validation_error_when_required_fields_are_missing()
    {
        $this->getJson(route(RouteName::FIXTURE_STATS))->assertStatus(422)->assertJsonValidationErrors(['id']);
    }

    public function test_will_return_not_found_status_code_when_fixture_does_not_exists()
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(33)->assertNotFound();
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

        $this->withoutExceptionHandling()
            ->getTestResponse(34)
            ->assertSuccessful()
            ->assertJsonCount(15, 'data.stats.0.stats')
            ->assertJsonCount(15, 'data.stats.1.stats')
            ->assertJsonCount(2, 'data.stats');
    }

    public function test_will_return_403_status_code_when_fixture_statistics_is_not_supported()
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::set($json, 'response.0.seasons.9.coverage.fixtures.statistics_fixtures', false); //use the same season year with fixture stub league season year
        Arr::set($json, 'response.0.seasons', [Arr::get($json, 'response.0.seasons.9')]);

        Http::fakeSequence()->push(FetchFixtureResponse::json())->push(json_encode($json));

        $this->getTestResponse(34)->assertStatus(403);
    }

    public function test_will_return_statistics_for_only_one_team(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()
            ->getTestResponse(215622, ['team' => $id = $this->hashId(458)])
            ->assertSuccessful()
            ->assertJsonCount(1, 'data.stats')
            ->assertJson(function (AssertableJson $assertableJson) use ($id) {
                $assertableJson->where('data.stats.0.team.attributes.id', $id)->etc();
            });
    }

    public function test_will_throw_exception_when_requested_team_is_not_a_team_in_fixture(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(215622, ['team' => $this->hashId(45800)]) //the id is not in json stub
            ->assertStatus(400);
    }

    public function test_will_return_partial_resource(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()->getTestResponse(215622, ['fields' => 'fouls,corners,shots'])
            ->assertSuccessful()
            ->assertJsonCount(3, 'data.stats.0.stats')
            ->assertJsonCount(3, 'data.stats.1.stats')
            ->assertJsonStructure([
                'data' => [
                    'stats' => [
                        [
                            'stats' => [
                                'fouls',
                                'corners',
                                'shots'
                            ]
                        ],
                        [
                            'stats' => [
                                'fouls',
                                'corners',
                                'shots',
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_will_return_validation_error_when_partial_resource_field_is_invalid(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(215622, ['fields' => 'fouls,corners,shots,foo'])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('fields');
    }

    public function test_will_return_validation_error_when_partial_resource_field_is_empty(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(215622, ['fields'])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('fields');
    }

    public function test_will_return_validation_error_when_partial_resource_field_is_an_array(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(215622, ['fields[]=shots', 'fields[]=cards'])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('fields');
    }

    public function test_empty_fixture_statistics_http_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureStatisticsResponse::noContent())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(34)->assertSuccessful()->assertJsonCount(0, 'data');
    }
}
