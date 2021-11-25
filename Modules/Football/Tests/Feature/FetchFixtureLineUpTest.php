<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureLineUpRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureLineUpResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchInjuriesResponse;

class FetchFixtureLineUpTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixtureLineUpRoute(new FixtureId($id))
        );
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
            ->push(FetchInjuriesResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()
            ->getTestRespone(34)
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
            ->whenEmpty(Http::response($json));

        $this->getTestRespone(34)->assertStatus(204);
    }
}
