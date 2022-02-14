<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Http\Resources;

use Tests\TestCase;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Module\Football\DTO\PlayerStatistics;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\PlayerFactory;
use Module\Football\DTO\Builders\PlayerStatisticBuilder;
use Module\Football\Http\Resources\PartialFixturePlayersStatisticsResource;

class PartialFixturePlayersStatisticsResourceTest extends TestCase
{
    public function test_will_return_full_response_when_no_fields_are_requested(): void
    {
        $this->getTestReponse([])
            ->assertJsonCount(11, 'data.attributes')
            ->assertJsonCount(3, 'data.attributes.cards')
            ->assertJsonCount(3, 'data.attributes.dribbles')
            ->assertJsonCount(2, 'data.attributes.goals')
            ->assertJsonCount(2, 'data.attributes.shots')
            ->assertJsonCount(3, 'data.attributes.passes');
    }

    public function test_will_partial_response(): void
    {
        $wrap = fn (mixed $value): array => [
            'data' => [
                'attributes' => [...Arr::wrap($value), 'player']
            ]
        ];

        $this->getTestReponse(['cards'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(3, 'data.attributes.cards')
            ->assertJsonStructure($wrap(['cards' => ['yellow', 'red', 'total']]));

        $this->getTestReponse(['cards.yellow'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.cards')
            ->assertJsonStructure($wrap(['cards' => ['yellow']]));

        $this->getTestReponse(['cards.red'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.cards')
            ->assertJsonStructure($wrap(['cards' => ['red']]));

        $this->getTestReponse(['cards.total'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.cards')
            ->assertJsonStructure($wrap(['cards' => ['total']]));

        $this->getTestReponse(['cards.yellow', 'cards.red'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.cards')
            ->assertJsonStructure($wrap(['cards' => ['yellow', 'red']]));

        $this->getTestReponse(['rating'])->assertJsonCount(2, 'data.attributes')->assertJsonStructure($wrap('rating'));
        $this->getTestReponse(['minutes_played'])->assertJsonCount(2, 'data.attributes')->assertJsonStructure($wrap('minutes_played'));
        $this->getTestReponse(['offsides'])->assertJsonCount(2, 'data.attributes')->assertJsonStructure($wrap('offsides'));
        $this->getTestReponse(['interception'])->assertJsonCount(2, 'data.attributes')->assertJsonStructure($wrap('interception'));

        $this->getTestReponse(['dribbles'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(3, 'data.attributes.dribbles')
            ->assertJsonStructure($wrap(['dribbles' => ['attempts', 'successful', 'past']]));

        $this->getTestReponse(['dribbles.attempts'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.dribbles')
            ->assertJsonStructure($wrap(['dribbles' => ['attempts']]));

        $this->getTestReponse(['dribbles.successful'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.dribbles')
            ->assertJsonStructure($wrap(['dribbles' => ['successful']]));

        $this->getTestReponse(['dribbles.past'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.dribbles')
            ->assertJsonStructure($wrap(['dribbles' => ['past']]));

        $this->getTestReponse(['dribbles.past', 'dribbles.attempts'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.dribbles')
            ->assertJsonStructure($wrap(['dribbles' => ['past', 'attempts']]));

        $this->getTestReponse(['goals'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.goals')
            ->assertJsonStructure($wrap(['goals' => ['total', 'assists']]));

        $this->getTestReponse(['goals.total'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.goals')
            ->assertJsonStructure($wrap(['goals' => ['total']]));

        $this->getTestReponse(['goals.assists'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.goals')
            ->assertJsonStructure($wrap(['goals' => ['assists']]));

        $this->getTestReponse(['goals.total', 'goals.assists'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.goals')
            ->assertJsonStructure($wrap(['goals' => ['total', 'assists']]));

        //goalie stats for non goalie
        $this->getTestReponse(['goals.saves'])->assertJsonCount(2, 'data.attributes')->assertJsonCount(0, 'data.attributes.goals');
        $this->getTestReponse(['goals.conceeded'])->assertJsonCount(2, 'data.attributes')->assertJsonCount(0, 'data.attributes.goals');

        //goalie stats
        $this->getTestReponseForGoalie(['goals'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(4, 'data.attributes.goals')
            ->assertJsonStructure($wrap(['goals' => ['total', 'assists', 'saves', 'conceeded']]));

        $this->getTestReponse(['shots'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.shots')
            ->assertJsonStructure($wrap(['shots' => ['on_target', 'total']]));

        $this->getTestReponse(['shots.on_target'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.shots')
            ->assertJsonStructure($wrap(['shots' => ['on_target']]));

        $this->getTestReponse(['shots.total', 'shots.on_target'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.shots')
            ->assertJsonStructure($wrap(['shots' => ['on_target', 'total']]));

        $this->getTestReponse(['shots.total'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.shots')
            ->assertJsonStructure($wrap(['shots' => ['total']]));

        $this->getTestReponse(['passes'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(3, 'data.attributes.passes')
            ->assertJsonStructure($wrap(['passes' => ['accuracy', 'key', 'total']]));

        $this->getTestReponse(['passes.accuracy'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.passes')
            ->assertJsonStructure($wrap(['passes' => ['accuracy']]));

        $this->getTestReponse(['passes.total', 'passes.key'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(2, 'data.attributes.passes')
            ->assertJsonStructure($wrap(['passes' => ['total', 'key']]));

        $this->getTestReponse(['passes.key'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.passes')
            ->assertJsonStructure($wrap(['passes' => ['key']]));

        $this->getTestReponse(['passes.total'])
            ->assertJsonCount(2, 'data.attributes')
            ->assertJsonCount(1, 'data.attributes.passes')
            ->assertJsonStructure($wrap(['passes' => ['total']]));

        $this->getTestReponse(['passes.total', 'rating', 'offsides', 'shots'])
            ->assertJsonCount(5, 'data.attributes')
            ->assertJsonStructure($wrap([
                'passes' => ['total'],
                'rating',
                'offsides',
                'shots'
            ]));
    }

    private function getTestReponse(array $filters): TestResponse
    {
        $statistics =  (new PlayerStatisticBuilder())
            ->cards(1, 2)
            ->dribbles(5, 1, 0)
            ->goals(0, 0)
            ->interceptions(0)
            ->minutesPlayed(90)
            ->offsides(0)
            ->shots(5, 6)
            ->passes(0, 0, 0)
            ->player(PlayerFactory::new()->midfielder()->toDto())
            ->rating(5.0)
            ->team(TeamFactory::new()->toDto())
            ->build();

        return $this->buildTestReponse($filters, $statistics);
    }

    private function getTestReponseForGoalie(array $filters): TestResponse
    {
        $statistics =  (new PlayerStatisticBuilder())
            ->cards(1, 2)
            ->dribbles(5, 1, 0)
            ->goals(0, 0)
            ->interceptions(0)
            ->minutesPlayed(90)
            ->offsides(0)
            ->shots(5, 6)
            ->passes(0, 0, 0)
            ->player(PlayerFactory::new()->goalKeeper()->toDto())
            ->rating(5.0)
            ->goalKeeperGoalStat(0, 6)
            ->team(TeamFactory::new()->toDto())
            ->build();

        return $this->buildTestReponse($filters, $statistics);
    }

    private function buildTestReponse(array $filters, PlayerStatistics $playerStatistics): TestResponse
    {
        request()->merge([
            'filter' => $filters,
        ]);

        $resource = new PartialFixturePlayersStatisticsResource($playerStatistics);

        return new TestResponse(new Response(
            $resource->toResponse(Request::createFromBase(request()))->content()
        ));
    }
}
