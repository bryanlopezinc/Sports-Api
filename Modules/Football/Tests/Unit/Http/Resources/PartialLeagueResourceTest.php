<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Resources;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Http\Resources\PartialLeagueResource;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class PartialLeagueResourceTest extends TestCase
{
    public function test_will_return_all_response_when_no_fields_are_requested(): void
    {
        $this->getTestReponse([])
            ->assertJsonCount(3, 'data')
            ->assertJsonCount(5, 'data.attributes')
            ->assertJsonCount(5, 'data.attributes.season')
            ->assertJsonCount(5, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data' => [
                    "type",
                    "attributes" => [
                        "id",
                        "logo_url",
                        "name",
                        "country",
                        "season" => [
                            "season",
                            "start",
                            "end",
                            "is_current_season",
                            "coverage" =>  [
                                "line_up",
                                "events",
                                "stats",
                                "top_scorers",
                                "top_assists",
                            ]
                        ]
                    ],
                    "links" =>  [
                        "self",
                        "top_scorers",
                        "top_assists",
                    ]
                ]
            ]);
    }

    public function test_will_return_only_coverage_and_season_attributes(): void
    {
        $this->getTestReponse(['season', 'coverage'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(5, 'data.attributes.season')
            ->assertJsonCount(5, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "season",
                            "start",
                            "end",
                            "is_current_season",
                            "coverage" =>  [
                                "line_up",
                                "events",
                                "stats",
                                "top_scorers",
                                "top_assists",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_attributes_combination_2(): void
    {
        $this->getTestReponse(['name', 'season.start', 'coverage.stats', 'links'])
            ->assertJsonCount(3, 'data')
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.season')
            ->assertJsonCount(1, 'data.attributes.season.coverage')
            ->assertJsonCount(3, 'data.links')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "name",
                        "season" => [
                            "start",
                            "coverage" =>  [
                                "stats",
                            ]
                        ]
                    ],
                    "links" =>  [
                        "self",
                        "top_scorers",
                        "top_assists",
                    ]
                ]
            ]);
    }

    public function test_will_return_only_attributes_combination_1(): void
    {
        $this->getTestReponse(['name', 'season.start', 'coverage.stats'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.season')
            ->assertJsonCount(1, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "name",
                        "season" => [
                            "start",
                            "coverage" =>  [
                                "stats",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_coverage_line_up_attribute(): void
    {
        $this->getTestReponse(['coverage.line_up'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonCount(1, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "coverage" =>  [
                                "line_up",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_coverage_events_attribute(): void
    {
        $this->getTestReponse(['coverage.events'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonCount(1, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "coverage" =>  [
                                "events",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_stats_attribute(): void
    {
        $this->getTestReponse(['coverage.stats'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonCount(1, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "coverage" =>  [
                                "stats",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_coverage_top_scorers_attribute(): void
    {
        $this->getTestReponse(['coverage.top_scorers'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonCount(1, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "coverage" =>  [
                                "top_scorers",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_coverage_top_assists_attribute(): void
    {
        $this->getTestReponse(['coverage.top_assists'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonCount(1, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "coverage" =>  [
                                "top_assists",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_coverage_attribute(): void
    {
        $this->getTestReponse(['coverage'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonCount(5, 'data.attributes.season.coverage')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "coverage" =>  [
                                "line_up",
                                "events",
                                "stats",
                                "top_scorers",
                                "top_assists",
                            ]
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_season_current_season_attribute(): void
    {
        $this->getTestReponse(['season.is_current_season'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            'is_current_season'
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_season_end_date_attribute(): void
    {
        $this->getTestReponse(['season.end'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            'end'
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_returns_only_season_start_date_attribute(): void
    {
        $this->getTestReponse(['season.start'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            'start'
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_season_year_attribute(): void
    {
        $this->getTestReponse(['season.season'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.season')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            'season'
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_season_attribute(): void
    {
        $this->getTestReponse(['season'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonCount(4, 'data.attributes.season')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        "season" => [
                            "season",
                            "start",
                            "end",
                            "is_current_season",
                        ]
                    ],
                ]
            ]);
    }

    public function test_will_return_only_country_attribute(): void
    {
        $this->getTestReponse(['country'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        'country'
                    ],
                ]
            ]);
    }

    public function test_will_return_only_logo_url_attribute(): void
    {
        $this->getTestReponse(['logo_url'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        'logo_url'
                    ],
                ]
            ]);
    }

    public function test_will_return_only_name_attribute(): void
    {
        $this->getTestReponse(['name'])
            ->assertJsonCount(2, 'data')
            ->assertJsonCount(1, 'data.attributes')
            ->assertJsonStructure([
                'data'  => [
                    "type",
                    "attributes" => [
                        'name'
                    ],
                ]
            ]);
    }

    private function getTestReponse(array $filters): TestResponse
    {
        $request = new SymfonyRequest([
            'filter' => $filters,
        ]);

        $request->setMethod(SymfonyRequest::METHOD_GET);

        $resource = (new PartialLeagueResource(LeagueFactory::new()->toDto(), 'filter'));

        return new TestResponse(new Response(
            $resource->toResponse(Request::createFromBase($request))->content()
        ));
    }
}
