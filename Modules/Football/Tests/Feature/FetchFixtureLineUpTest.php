<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureLineUpRoute;
use Module\Football\Routes\RouteName;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureLineUpResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchInjuriesResponse;

class FetchFixtureLineUpTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixtureLineUpRoute(new FixtureId($id))
        );
    }

    public function test_will_throw_validation_error_when_required_fields_are_missing()
    {
        $this->getJson(route(RouteName::FIXTURE_LINEUP))->assertStatus(422)->assertJsonValidationErrors(['id']);
    }

    public function test_will_return_not_found_status_code_when_fixture_does_not_exists()
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(33)->assertNotFound();
    }

    /**
     * @test
     */
    public function FetchFixtureLineUp_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureLineUpResponse::json())
            ->push(FetchInjuriesResponse::json());

        $this->withoutExceptionHandling()
            ->getTestResponse(215662)
            ->assertSuccessful()
            ->assertJsonStructure([
                'data'  => [
                    'type',
                    'fixture_id',
                    'line_up'   => [
                        'home'  => [
                            'team',
                            'formation',
                            'starting_XI',
                            'subs',
                            'coach',
                            'missing_players',
                        ],
                        'away'  => [
                            'team',
                            'formation',
                            'starting_XI',
                            'subs',
                            'coach',
                            'missing_players',
                        ]
                    ]
                ]
            ]);
    }

    public function test_line_up_not_available(): void
    {
        $json = json_encode([
            "get"   => "fixtures/lineups",
            "parameters"    => [
                "fixture"   => "686314"
            ],
            "errors"    => [],
            "results"   => 0,
            'response'  => []
        ]);

        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push($json)
            ->push(FetchInjuriesResponse::json());

        $this->getTestResponse(215662)->assertSuccessful()->assertJsonCount(0, 'data.line_up');
    }

    public function test_will_return_403_status_code_when_fixture_lineup_is_not_supported()
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::set($json, 'response.0.seasons.9.coverage.fixtures.lineups', false); //use the same season year with fixture stub league season year
        Arr::set($json, 'response.0.seasons', [Arr::get($json, 'response.0.seasons.9')]);

        Http::fakeSequence()->push(FetchFixtureResponse::json())->push(json_encode($json));

        $this->getTestResponse(34)->assertStatus(403);
    }
}
