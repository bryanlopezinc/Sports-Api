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
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(
            (string) new FetchLeagueRoute(new LeagueId($id))
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fake(fn () => Http::response(FetchLeagueResponse::json()));

        $this->getTestRespone(234)
            ->assertSuccessful()
            ->assertHeader('max-age')
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
}
