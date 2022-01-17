<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Routes\FetchLeagueTopScorersRoute;
use Module\Football\Routes\RouteName;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTopScorersResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueFixturesByDateResponse;

class FetchLeagueTopScorersTest extends TestCase
{
    private function getTestResponse(int $id, int $season): TestResponse
    {
        return $this->getJson(
            (string) new FetchLeagueTopScorersRoute(new LeagueId($id), new Season($season))
        );
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(FetchTopScorersResponse::json())
            ->push(FetchLeagueFixturesByDateResponse::json());

        $this->withoutExceptionHandling()
            ->getTestResponse(34, 2020)
            ->assertSuccessful();
    }

    public function test_will_throw_validation_error_when_required_fields_are_missing()
    {
        $this->getJson(route(RouteName::LEAGUE_TOP_SCORERS))->assertStatus(422)->assertJsonValidationErrors(['id', 'season']);
    }

    public function test_will_return_403_status_code_when_league_top_scorers_is_not_supported()
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::set($json, 'response.0.seasons.11.coverage.top_scorers', false); //edit 2021 season
        Arr::set($json, 'response.0.seasons', [Arr::get($json, 'response.0.seasons.11')]);

        Http::fakeSequence()->push(json_encode($json));

        //season parameter should match edited season
        $this->getTestResponse(34, 2021)->assertStatus(403);
    }

    public function test_empty_league_top_scorers_http_response(): void
    {
        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(FetchTopScorersResponse::noContent())
            ->push(FetchLeagueFixturesByDateResponse::json());

        $this->getTestResponse(34, 2020)->assertSuccessful()->assertJsonCount(0, 'data');
    }
}
