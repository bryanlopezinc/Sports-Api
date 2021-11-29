<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Resources;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Testing\AssertableJsonString;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Http\Resources\PartialLeagueResource;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class PartialLeagueResourceTest extends TestCase
{
    private PartialLeagueResource $resource;
    private AssertableJsonString $assertableJson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = new PartialLeagueResource(LeagueFactory::new()->toDto(), 'filter');
        $this->assertableJson = new AssertableJsonString([]);
    }

    public function test_returns_all_response_when_no_fields_are_requested(): void
    {
        $request = new SymfonyRequest();
        $request->setMethod(SymfonyRequest::METHOD_GET);

        $this->assertableJson->assertStructure([
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
        ], $this->resource->toArray(Request::createFromBase($request)));
    }

    public function test_returns_all_attributes_when_only_id_attribute_is_requested(): void
    {
        $this->assertableJson->assertStructure([
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
        ], $this->resource->toArray($this->buildRequest('id')));
    }

    public function test_returns_all_attributes_when_invalid_fields_are_requested(): void
    {
        $this->assertableJson->assertStructure(["type",
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
        ], $this->resource->toArray($this->buildRequest('foo,bar')));
    }

    public function test_returns_only_coverage_and_season_attributes(): void
    {
        $this->assertableJson->assertStructure([
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
        ], $this->resource->toArray($this->buildRequest('season,coverage')));
    }

    public function test_returns_only_a_coverage_dattribute_when_coverage_and_a_coverage_data_is_requested(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "coverage" =>  [
                        "stats",
                    ]
                ]
            ],
        ], $this->resource->toArray($this->buildRequest('coverage,coverage.stats')));
    }

    public function test_returns_only_a_season_attribute_when_full_season_data_and_a_season_data_is_requested(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "start",
                ]
            ],
        ], $this->resource->toArray($this->buildRequest('season,season.start')));
    }

    public function test_returns_only_attributes_combination_2(): void
    {
        $this->assertableJson->assertStructure([
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
        ], $this->resource->toArray($this->buildRequest('name,season.start,coverage.stats,links')));
    }

    public function test_returns_only_attributes_combination_1(): void
    {
        $this->assertableJson->assertStructure([
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
        ], $this->resource->toArray($this->buildRequest('name,season.start,coverage.stats')));
    }

    public function test_returns_only_coverage_line_up_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "coverage" =>  [
                        "line_up",
                    ]
                ]
            ],
        ], $this->resource->toArray($this->buildRequest('coverage.line_up')));
    }

    public function test_returns_only_coverage_events_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "coverage" =>  [
                        "events",
                    ]
                ]
            ],
        ], $this->resource->toArray($this->buildRequest('coverage.events')));
    }

    public function test_returns_only_stats_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "coverage" =>  [
                        "stats",
                    ]
                ]
            ],
        ], $this->resource->toArray($this->buildRequest('coverage.stats')));
    }

    public function test_returns_only_coverage_top_scorers_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "coverage" =>  [
                        "top_scorers",
                    ]
                ]
            ],
        ], $this->resource->toArray($this->buildRequest('coverage.top_scorers')));
    }

    public function test_returns_only_coverage_top_assists_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "coverage" =>  [
                        "top_assists",
                    ]
                ]
            ],
        ], $this->resource->toArray($this->buildRequest('coverage.top_assists')));
    }

    public function test_returns_only_coverage_attribute(): void
    {
        $this->assertableJson->assertStructure([
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
        ], $this->resource->toArray($this->buildRequest('coverage')));
    }

    public function test_returns_only_season_current_season_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "is_current_season",
                ],
            ],
        ], $this->resource->toArray($this->buildRequest('season.is_current_season')));
    }

    public function test_returns_only_season_end_date_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "end",
                ],
            ],
        ], $this->resource->toArray($this->buildRequest('season.end')));
    }

    public function test_returns_only_season_date_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "start",
                ],
            ],
        ], $this->resource->toArray($this->buildRequest('season.start')));
    }

    public function test_returns_only_season_year_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "season",
                ],
            ],
        ], $this->resource->toArray($this->buildRequest('season.season')));
    }

    public function test_returns_only_season_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "season" => [
                    "season",
                    "start",
                    "end",
                    "is_current_season",
                ],
            ],
        ], $this->resource->toArray($this->buildRequest('season')));
    }

    public function test_returns_only_country_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "country",
            ],
        ], $this->resource->toArray($this->buildRequest('logo_url')));
    }

    public function test_returns_only_logo_url_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "logo_url",
            ],
        ], $this->resource->toArray($this->buildRequest('logo_url')));
    }

    public function test_returns_only_name_attribute(): void
    {
        $this->assertableJson->assertStructure([
            "type",
            "attributes" => [
                "name",
            ],
        ], $this->resource->toArray($this->buildRequest('name')));
    }

    private function buildRequest(string $filters): Request
    {
        $request = new SymfonyRequest(['filter=' . $filters]);
        $request->setMethod(SymfonyRequest::METHOD_GET);

        return Request::createFromBase($request);
    }
}
