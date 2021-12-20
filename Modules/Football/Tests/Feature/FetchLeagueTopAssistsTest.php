<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Routes\FetchLeagueTopAssistsRoute;
use Module\Football\Routes\Name;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTopAssistsResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueFixturesByDateResponse;

class FetchLeagueTopAssistsTest extends TestCase
{
    private function getTestResponse(int $id, int $season): TestResponse
    {
        return $this->getJson(
            (string) new FetchLeagueTopAssistsRoute(new LeagueId($id), new Season($season))
        );
    }

    public function test_will_throw_validation_error_when_required_fields_are_missing()
    {
        $this->getJson(route(Name::FETCH_LEAGUE_TOP_ASSISTS))->assertStatus(422)->assertJsonValidationErrors(['id']);
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(FetchTopAssistsResponse::json())
            ->push(FetchLeagueFixturesByDateResponse::json());

        $this->withoutExceptionHandling()
            ->getTestResponse(34, 2020)
            ->assertSuccessful();
    }

    public function test_throws_exception_when_top_assists_is_not_yet_available(): void
    {
        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(status: 204);

        $this->getTestResponse(34, 2020)
            ->assertNoContent();
    }

    public function test_will_return_403_status_code_when_league_top_assists_is_not_supported()
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::set($json, 'response.0.seasons.11.coverage.top_assists', false); //edit 2021 season
        Arr::set($json, 'response.0.seasons', [Arr::get($json, 'response.0.seasons.11')]);

        Http::fakeSequence()->push(json_encode($json));

        //season parameter should match edited season
        $this->getTestResponse(34, 2021)->assertStatus(403);
    }
}
