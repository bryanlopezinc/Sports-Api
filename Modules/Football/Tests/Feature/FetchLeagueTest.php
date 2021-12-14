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
    private function getTestRespone(int $id, array $query = []): TestResponse
    {
        return $this->getJson(
            (string) new FetchLeagueRoute(new LeagueId($id), $query)
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $this->getTestRespone(234)
            ->assertSuccessful()
            ->assertHeader('max-age')
            ->assertJsonCount(3)
            ->assertJsonCount(5, 'attributes')
            ->assertJsonCount(5, 'attributes.season')
            ->assertJsonCount(5, 'attributes.season.coverage')
            ->assertJsonStructure(
                [
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
            );
    }

    public function test_will_return_partial_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $this->getTestRespone(234, ['filter' => 'name,country'])
            ->assertSuccessful()
            ->assertJsonCount(2)
            ->assertJsonCount(2, 'attributes')
            ->assertJsonStructure(
                [
                    "type",
                    "attributes" => [
                        "name",
                        "country",
                    ],
                ]
            );
    }
}
