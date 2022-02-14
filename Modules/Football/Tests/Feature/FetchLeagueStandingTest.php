<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueStandingResponse;

class FetchLeagueStandingTest extends TestCase
{
    private function getTestResponse(int $id, int $season, array $query = []): TestResponse
    {
        $parameters = array_merge($query, [
            'league_id'     => $this->hashId($id),
            'season'        => $season
        ]);

        return $this->getJson(route(RouteName::LEAGUE_STANDING, $parameters));
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()->push(FetchLeagueResponse::json())->push(FetchLeagueStandingResponse::json());

        $this->getTestResponse(39, 2018)->assertSuccessful()->assertJsonCount(20, 'data.standings');
    }

    public function test_will_return_404_status_code_when_league_id_does_not_exists(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(334, 2018)->assertNotFound();
    }

    public function test_will_return_validation_error_when_teams_are_invalid()
    {
        Http::fakeSequence()->push(FetchLeagueResponse::json());

        $this->getTestResponse(400, 2018, ['teams' => '22,foo,40'])->assertStatus(422);
    }

    public function test_will_return_validation_error_when_there_are_duplicate_teams()
    {
        Http::fakeSequence()->push(FetchLeagueResponse::json());

        $teamIds = collect([22, 40, 22])->map(fn (int $num) => $this->hashId($num))->implode(',');

        $this->getTestResponse(400, 2018, ['teams' => $teamIds])->assertStatus(422);
    }

    public function test_will_throw_validation_error_when_atributes_are_missing()
    {
        $this->getJson(route(RouteName::LEAGUE_STANDING))->assertStatus(422)->assertJsonValidationErrors(['league_id', 'season']);
    }

    public function test_will_return_validation_error_if_a_requested_team_id_does_not_exists_in_league_table()
    {
        Http::fakeSequence()->push(FetchLeagueResponse::json()) ->push(FetchLeagueStandingResponse::json());

        $this->getTestResponse(39, 2018, [
            'teams' => $this->hashId(4063), // team id does not exists in leagueTable.json stub
        ])->assertStatus(400);
    }

    public function test_will_return_403_status_code_when_league_standing_is_not_supported()
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::set($json, 'response.0.seasons.11.coverage.standings', false); //edit 2021 season
        Arr::set($json, 'response.0.seasons', [Arr::get($json, 'response.0.seasons.11')]);

        Http::fakeSequence()->push(json_encode($json));

        //season parameter should match edited season
        $this->getTestResponse(39, 2021)->assertStatus(403);
    }

    public function test_will_return_partial_response()
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()->push(FetchLeagueResponse::json())->push(FetchLeagueStandingResponse::json());

        $response = $this->getTestResponse(39, 2018, [
            'teams'         => collect([40, 63])->map(fn (int $num) => $this->hashId($num))->implode(','), // team ids from leagueTable.json stub
            'fields'        => 'league,position,points',
            'league_fields' => 'name'
        ])
            ->assertSuccessful()
            ->assertJsonCount(2, 'data.standings')
            ->assertJsonCount(2, 'data.league')
            ->assertJsonCount(1, 'data.league.attributes')
            ->assertJsonCount(3, 'data.standings.0')
            ->assertJsonCount(3, 'data.standings.1');

        $response->assertJsonStructure([
            'points',
            'position',
            'team'
        ], $response->json('data.standings.0'));

        $response->assertJsonStructure([
            'type',
            'attributes' => [
                'name'
            ],
        ], $response->json('data.league'));
    }
}
