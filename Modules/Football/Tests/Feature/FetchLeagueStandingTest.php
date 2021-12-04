<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\Name;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureByDateResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueStandingResponse;

/**
 * @group 112
 */
class FetchLeagueStandingTest extends TestCase
{
    private function getTestResponse(int $id, int $season, array $query = []): TestResponse
    {
        $parameters = array_merge($query, [
            'league_id'     => $id,
            'season'        => $season
        ]);

        return $this->getJson(route(Name::FETCH_LEAGUE_STANDING, $parameters));
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(FetchLeagueStandingResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureByDateResponse::json());

        $this->getTestResponse(400, 2018)->assertSuccessful();
    }

    public function test_will_return_validation_error_when_teams_are_invalid()
    {
        Http::fakeSequence()->push(FetchLeagueResponse::json());

        $this->getTestResponse(400, 2018, ['teams' => '22,foo,40'])->assertStatus(422);
    }

    public function test_will_return_validation_error_when_there_are_duplicate_teams()
    {
        Http::fakeSequence()->push(FetchLeagueResponse::json());

        $this->getTestResponse(400, 2018, ['teams' => '22,40,40'])->assertStatus(422);
    }

    public function test_will_return_validation_error_if_a_requested_team_id_does_not_exists_in_league_table()
    {
        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(FetchLeagueStandingResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureByDateResponse::json());

        $this->getTestResponse(400, 2018, [
            'teams'         => '4063', // team id does not exists in leagueTable.json stub
        ])->assertStatus(400);
    }

    public function test_will_return_partial_response()
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()
            ->push(FetchLeagueResponse::json())
            ->push(FetchLeagueStandingResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixtureByDateResponse::json());

        $response = $this->getTestResponse(400, 2018, [
            'teams'         => '40,63', // team ids from leagueTable.json stub
            'fields'        => 'league,position,points',
            'league_fields' => 'name'
        ])
            ->assertSuccessful()
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
