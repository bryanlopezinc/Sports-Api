<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Routes\FetchFixtureRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;

class FetchFixtureTest extends TestCase
{
    private function getTestResponse(int $id, array $query = []): TestResponse
    {
        return $this->getJson(
            (string)new FetchFixtureRoute(new FixtureId($id), $query)
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()->push(FetchFixtureResponse::json())->push(FetchLeagueResponse::json());

        $this->getTestResponse(12)
            ->assertSuccessful()
            ->assertHeader('max-age')
            ->assertJsonCount(4, 'data')
            ->assertJsonCount(14, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.referee')
            ->assertJsonCount(2, 'data.attributes.score')
            ->assertJsonCount(2, 'data.attributes.teams')
            ->assertJsonCount(5, 'data.links')
            ->assertJsonCount(4, 'data.attributes.period_goals.meta')
            ->assertJsonCount(1, 'data.user')
            ->assertJsonStructure([
                'data'  => [
                    'type',
                    'user' => [
                        'has_predicted'
                    ],
                    'attributes' => [
                        'id',
                        'referee'   => [
                            'name_is_availbale',
                            'name',
                        ],
                        'date',
                        'has_venue_info',
                        'venue',
                        'minutes_elapsed',
                        'status',
                        'league',
                        'has_winner',
                        'winner',
                        'teams' => [
                            'home',
                            'away'
                        ],
                        'score_is_available',
                        'score' => [
                            'home',
                            'away'
                        ],
                        'period_goals' => [
                            'meta' => [
                                'has_first_half_score',
                                'has_full_time_score',
                                'has_extra_time_score',
                                'has_penalty_score'
                            ],
                            'first_half',
                            'second_half',
                        ],
                    ],
                    'links' => [
                        'self',
                        'events',
                        'line_up',
                        'stats',
                        'players_stats',
                    ]
                ]
            ]);
    }

    public function test_will_return_partial_response_when_needed(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()->push(FetchFixtureResponse::json())->push(FetchLeagueResponse::json());

        $this->getTestResponse(34, ['filter' => 'date,status'])
            ->assertSuccessful()
            ->assertJsonCount(1)
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonStructure([
                'data' => [
                    "type",
                    "attributes" => [
                        "date",
                        "status" => [
                            "info",
                            "short"
                        ]
                    ]
                ]
            ]);
    }
}
