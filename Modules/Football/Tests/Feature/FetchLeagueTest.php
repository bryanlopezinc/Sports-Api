<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Routes\FetchLeagueRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;

class FetchLeagueTest extends TestCase
{
    private function getTestResponse(int $id, array $query = []): TestResponse
    {
        return $this->getJson(
            (string) new FetchLeagueRoute(new LeagueId($id), $query)
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $this->getTestResponse(234)
            ->assertSuccessful()
            ->assertJsonCount(5, 'data.attributes')
            ->assertJsonCount(5, 'data.attributes.season')
            ->assertJsonCount(5, 'data.attributes.season.coverage')
            ->assertJsonStructure(
                [
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
                ]
            );
    }

    public function test_will_return_404_status_code_when_league_id_does_not_exists(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(334)->assertNotFound();
    }

    public function test_will_return_partial_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $this->getTestResponse(234, ['filter' => 'name,country'])
            ->assertSuccessful()
            ->assertJsonCount(1)
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonStructure(
                [
                    'data' => [
                        "type",
                        "attributes" => [
                            "name",
                            "country",
                        ],
                    ]
                ]
            );
    }
}
