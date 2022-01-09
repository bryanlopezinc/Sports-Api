<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Routes\FetchFixturePlayersStatisticsRoute;
use Module\Football\Routes\Name;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixturePlayersStatisticsResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;

class FetchFixturePlayersStatisticsTest extends TestCase
{
    private function getTestResponse(int $id, array $query = []): TestResponse
    {
        return $this->getJson(
            (string) new FetchFixturePlayersStatisticsRoute(new FixtureId($id), $query)
        );
    }

    public function test_will_throw_validation_error_when_required_fields_are_missing()
    {
        $this->getJson(route(Name::FETCH_FIXTURES_PLAYERS_STAT))->assertStatus(422)->assertJsonValidationErrors(['id']);
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixturePlayersStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->withoutExceptionHandling()
            ->getTestResponse(34)
            ->assertSuccessful()
            ->assertJsonCount(40, 'data');
    }

    public function test_will_return_only_statistics_for_players_in_a_particular_team(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixturePlayersStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $response = $this->getTestResponse(34, ['team' => $teamId = $this->hashId(157)]) //any team in json stub
            ->assertSuccessful()
            ->assertJsonCount(20, 'data');

        foreach ($response->json('data') as $data) {
            $this->assertEquals(Arr::get($data, 'attributes.team.attributes.id'), $teamId);
        }
    }

    public function test_will_return_400_status_code_when_requested_team_is_not_a_team_in_fixture(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixturePlayersStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $this->getTestResponse(34, ['team' => $this->hashId(1999)]) // team id that is not in json stub
            ->assertStatus(400);
    }

    public function test_will_return_partial_resource_statistics_for_players_statistics(): void
    {
        Http::fakeSequence()
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchFixturePlayersStatisticsResponse::json())
            ->push(FetchFixtureResponse::json())
            ->push(FetchLeagueResponse::json());

        $response = $this->getTestResponse(34, ['filter' => 'cards'])->assertSuccessful();

        foreach ($response->json('data') as $data) {
            (new TestResponse(new Response($data)))
                ->assertJsonCount(2, 'attributes')
                ->assertJsonCount(3, 'attributes.cards')
                ->assertJsonStructure([
                    'attributes' => [
                        'player',
                        'cards' => [
                            'yellow',
                            'red',
                            'total'
                        ]
                    ]
                ]);
        }
    }

    public function test_will_return_403_status_code_when_fixture_player_statistics_is_not_supported()
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::set($json, 'response.0.seasons.9.coverage.fixtures.statistics_players', false); //use the same season year with fixture stub league season year
        Arr::set($json, 'response.0.seasons', [Arr::get($json, 'response.0.seasons.9')]);

        Http::fakeSequence()->push(FetchFixtureResponse::json())->push(json_encode($json));

        $this->getTestResponse(34)->assertStatus(403);
    }
}
