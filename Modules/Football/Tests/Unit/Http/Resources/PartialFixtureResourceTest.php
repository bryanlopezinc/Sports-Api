<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Resources;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Module\Football\Factories\FixtureFactory;
use Module\Football\Http\Resources\PartialFixtureResource;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class PartialFixtureResourceTest extends TestCase
{
    private function makeFullResponseAssertions(TestResponse $testResponse): TestResponse
    {
        return $testResponse
            ->assertJsonCount(3, 'data')
            ->assertJsonCount(14, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.referee')
            ->assertJsonCount(2, 'data.attributes.score')
            ->assertJsonCount(2, 'data.attributes.teams')
            ->assertJsonCount(5, 'data.links')
            ->assertJsonCount(4, 'data.attributes.period_goals.meta')
            ->assertJsonStructure([
                'data'  => [
                    'type',
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

    public function test_will_return_full_response_when_no_fields_are_requested(): void
    {
        $this->makeFullResponseAssertions($this->getTestReponse([]));
    }

    public function test_will_return_full_response_when_only_id_is_requested(): void
    {
        $this->makeFullResponseAssertions($this->getTestReponse(['id']));
    }

    public function test_will_return_full_response_when_invalid_fields_are_requested(): void
    {
        $this->makeFullResponseAssertions($this->getTestReponse(['foo', 'bar']));
    }

    public function test_will_return_only_attributes_combination_1(): void
    {
        $this->getTestReponse(['teams', 'date'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.teams')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'teams'   => [
                            'home',
                            'away',
                        ],
                        'date',
                    ],
                ]
            ]);
    }

    public function test_will_return_only_attributes_combination_2(): void
    {
        $this->getTestReponse(['teams', 'score'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(3, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.teams')
            ->assertJsonCount(2, 'data.attributes.score')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'teams'   => [
                            'home',
                            'away',
                        ],
                        'score' => [
                            'home',
                            'away'
                        ],
                        'score_is_available'
                    ],
                ]
            ]);
    }

    public function test_will_return_only_referee_attributes(): void
    {
        $this->getTestReponse(['referee'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.referee')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'referee'   => [
                            'name_is_availbale',
                            'name',
                        ],
                    ],
                ]
            ]);
    }

    public function test_will_return_only_date_attribute(): void
    {
        $this->getTestReponse(['date'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'date',
                    ],
                ]
            ]);
    }

    public function test_will_return_only_venue_attributes(): void
    {
        $this->getTestReponse(['venue'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'venue',
                        'has_venue_info'
                    ],
                ]
            ]);
    }

    public function test_will_return_only_minutes_elapsed_attribute(): void
    {
        $this->getTestReponse(['minutes_elapsed'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'minutes_elapsed',
                    ],
                ]
            ]);
    }

    public function test_will_return_only_fixture_status_attribute(): void
    {
        $this->getTestReponse(['status'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.status')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'status' => [
                            'info',
                            'short'
                        ],
                    ],
                ]
            ]);
    }

    public function test_will_return_only_league_attribute(): void
    {
        $this->getTestReponse(['league'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'league',
                    ],
                ]
            ]);
    }

    public function test_will_return_only_fixture_winner_attributes(): void
    {
        $this->getTestReponse(['winner'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'winner',
                        'has_winner'
                    ],
                ]
            ]);
    }

    public function test_will_return_only_team_attributes(): void
    {
        $this->getTestReponse(['teams'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.teams')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'teams' => [
                            'home',
                            'away'
                        ],
                    ],
                ]
            ]);
    }

    public function test_will_returns_only_score_attributes(): void
    {
        $this->getTestReponse(['score'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'score_is_available',
                        'score'
                    ],
                ]
            ]);
    }

    public function test_will_return_only_period_goals_attributes(): void
    {
        $this->getTestReponse(['period_goals'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(4, 'data.attributes.period_goals.meta')
            ->assertJsonCount(3, 'data.attributes.period_goals')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
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
                ]
            ]);
    }

    public function test_will_return_only_first_period_goals_attributes(): void
    {
        $this->getTestReponse(['period_goals.first_half'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.period_goals.meta')
            ->assertJsonCount(2, 'data.attributes.period_goals')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'period_goals' => [
                            'meta' => [
                                'has_first_half_score',
                            ],
                            'first_half',
                        ],
                    ],
                ]
            ]);
    }

    public function test_will_return_only_first_and_second_period_goals_attributes(): void
    {
        $this->getTestReponse(['period_goals.first_half', 'period_goals.second_half'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.period_goals.meta')
            ->assertJsonCount(3, 'data.attributes.period_goals')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'period_goals' => [
                            'meta' => [
                                'has_first_half_score',
                                'has_full_time_score'
                            ],
                            'first_half',
                            'second_half',
                        ],
                    ],
                ]
            ]);
    }

    public function test_will_return_only_second_period_goals_attributes(): void
    {
        $this->getTestReponse(['period_goals.second_half'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.period_goals.meta')
            ->assertJsonCount(2, 'data.attributes.period_goals')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'period_goals' => [
                            'meta' => [
                                'has_full_time_score',
                            ],
                            'second_half',
                        ],
                    ],
                ]
            ]);
    }

    public function test_will_return_only_extra_period_goals_attributes(): void
    {
        $this->getTestReponse(['period_goals.extra_time'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.period_goals.meta')
            ->assertJsonCount(1, 'data.attributes.period_goals')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'period_goals' => [
                            'meta' => [
                                'has_extra_time_score',
                            ],
                        ],
                    ],
                ]
            ]);
    }

    public function test_will_return_only_penalty_period_goals_attributes(): void
    {
        $this->getTestReponse(['period_goals.penalty'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.period_goals.meta')
            ->assertJsonCount(1, 'data.attributes.period_goals')
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'attributes' => [
                        'period_goals' => [
                            'meta' => [
                                'has_penalty_score',
                            ],
                        ],
                    ],
                ]
            ]);
    }

    private function getTestReponse(array $filters, array $leagueFilters = []): TestResponse
    {
        $request = new SymfonyRequest([
            'filter' => implode(',', $filters),
            'league_filter' => implode(',', $leagueFilters)
        ]);

        $request->setMethod(SymfonyRequest::METHOD_GET);

        $resource = (new PartialFixtureResource(FixtureFactory::new()->toDto()))->setFilterInputName('filter');

        return new TestResponse(new Response(
            $resource->toResponse(Request::createFromBase($request))->content()
        ));
    }
}
