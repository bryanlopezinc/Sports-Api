<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Resources;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Module\Football\Factories\TeamFactory;
use Illuminate\Testing\AssertableJsonString;
use Module\Football\Collections\LeagueTable;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Factories\LeagueStandingFactory;
use Module\Football\DTO\Builders\LeagueStandingBuilder;
use Module\Football\Http\Resources\PartialLeagueStandingResource;

class PartialLeagueStandingResourceTest extends TestCase
{
    public function test_will_return_all_response_when_no_fields_are_requested(): void
    {
        $testResponse = $this->getTestReponse([]);

        $testResponse->assertJsonCount(2, 'data');

        foreach ($testResponse->decodeResponseJson()->json('data.standings') as $data) {
            $assert = new AssertableJsonString($data);

            $assert->assertCount(13)
                ->assertCount(6, 'home_record.data')
                ->assertCount(6, 'away_record.data')
                ->assertStructure([
                    'points',
                    'position',
                    'team',
                    'team_form',
                    'played',
                    'won',
                    'lost',
                    'draws',
                    'home_record'   => [
                        'data' => [
                            'played',
                            'win',
                            'lose',
                            'draw',
                            'goals_found',
                            'goals_against'
                        ]
                    ],
                    'away_record'   => [
                        'data' => [
                            'played',
                            'win',
                            'lose',
                            'draw',
                            'goals_found',
                            'goals_against'
                        ]
                    ],
                    'goal_difference',
                    'goals_found',
                    'goals_against',
                ]);
        }
    }

    public function test_will_return_points_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'points'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)->assertStructure([
                'points',
                'team',
            ]);
        }
    }

    public function test_will_return_position_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'position'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)->assertStructure([
                'position',
                'team',
            ]);
        }
    }

    public function test_will_return_team_form_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'team_form'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)->assertStructure([
                'team_form',
                'team',
            ]);
        }
    }

    public function test_will_return_games_played_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'played'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)->assertStructure([
                'played',
                'team',
            ]);
        }
    }

    public function test_will_return_games_won_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'won'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)->assertStructure([
                'won',
                'team',
            ]);
        }
    }

    public function test_will_return_games_lost_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'lost'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)->assertStructure([
                'lost',
                'team',
            ]);
        }
    }

    public function test_will_return_games_drawn_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'draws'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)->assertStructure([
                'draws',
                'team',
            ]);
        }
    }

    public function test_will_return_home_record_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'home_record'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))
                ->assertCount(2)
                ->assertCount(6, 'home_record.data')
                ->assertStructure([
                    'home_record'   => [
                        'data' => [
                            'played',
                            'win',
                            'lose',
                            'draw',
                            'goals_found',
                            'goals_against'
                        ]
                    ],
                    'team',
                ]);
        }
    }

    public function test_will_return_away_record_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'away_record'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))
                ->assertCount(2)
                ->assertCount(6, 'away_record.data')
                ->assertStructure([
                    'away_record'   => [
                        'data' => [
                            'played',
                            'win',
                            'lose',
                            'draw',
                            'goals_found',
                            'goals_against'
                        ]
                    ],
                    'team',
                ]);
        }
    }

    public function test_will_return_goals_difference_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'goal_difference'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)
                ->assertStructure([
                    'goal_difference',
                    'team',
                ]);
        }
    }

    public function test_will_return_goals_found_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'goals_found'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)
                ->assertStructure([
                    'goals_found',
                    'team',
                ]);
        }
    }

    public function test_will_return_fields_combination(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'team,team_form,points,position'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(4)
                ->assertStructure([
                    'team_form',
                    'points',
                    'position',
                    'team',
                ]);
        }
    }

    public function test_will_return_goals_against_and_team_fields(): void
    {
        $testResponse = $this->getTestReponse(['filter' => 'goals_against'])->assertJsonCount(1, 'data');

        foreach ($testResponse->json('data.standings') as $data) {
            (new AssertableJsonString($data))->assertCount(2)
                ->assertStructure([
                    'goals_against',
                    'team',
                ]);
        }
    }

    public function test_will_apply_league_filters(): void
    {
        $testResponse = $this->getTestReponse(['league_filter' => ['name'], 'filter' => 'league']);

        $assert = new AssertableJsonString($testResponse->json('data.league'));

        $assert->assertCount(2)
            ->assertCount(1, 'attributes')
            ->assertStructure([
                'type',
                'attributes' => [
                    'name'
                ],
            ]);
    }

    private function getTestReponse(array $query, LeagueTable $table = null): TestResponse
    {
        request()->merge($query);

        $table = $table ?: $this->generateTable();

        $resource = (new PartialLeagueStandingResource($table))
            ->setFilterInputName('filter')
            ->setLeagueFilterInputName('league_filter');

        return new TestResponse(new Response(
            $resource->toResponse(request())->content()
        ));
    }

    private function generateTable(): LeagueTable
    {
        $sequence = [];
        $league = LeagueFactory::new()->toDto();

        for ($rank = 1; $rank < 21; $rank++) {
            $sequence[] = (new LeagueStandingBuilder())->setTeam(TeamFactory::new()->toDto())->setTeamRank($rank)->setLeague($league)->toArray();
        }

        return LeagueStandingFactory::new()->count(20)->sequence(...$sequence)->toCollection();
    }
}
