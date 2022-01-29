<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Arr;
use Laravel\Passport\Passport;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Prediction\PredictFixtureRequest as PredictionPredictFixtureRequest;
use Module\User\Factories\UserFactory;
use Symfony\Component\HttpFoundation\Response;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;

class PredictFootballFixtureTest extends TestCase
{
    private function getTestResponse(int $id, string $prediction): TestResponse
    {
        return $this->postJson(route(RouteName::PREDICT_FIXTURE, [
            'fixture_id'    => $this->hashId($id),
            'prediction'    => $prediction
        ]));
    }

    public function test_will_throw_validation_error_when_atributes_are_missing_or_invalid()
    {
        Passport::actingAs(UserFactory::new()->create());

        $this->postJson(route(RouteName::PREDICT_FIXTURE))->assertStatus(422)->assertJsonValidationErrors(['fixture_id', 'prediction']);

         $this->postJson(route(RouteName::PREDICT_FIXTURE, [
            'fixture_id'    => $this->hashId(300),
            'prediction'    => 'foo'
        ]))->assertStatus(422)->assertJsonValidationErrors(['prediction']);
    }

    public function test_unauthorized_user_cannot_predict_fixture(): void
    {
        $this->getTestResponse(215662, $this->prediction())->assertUnauthorized();
    }

    public function test_will_return_not_found_status_code_when_fixture_does_not_exists()
    {
        Http::fake(fn () => Http::response(status: 404));

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse(33, $this->prediction())->assertNotFound();
    }

    /**
     * @dataProvider requestData
     */
    public function test_success_response(string $fixtureJson): void
    {
        Http::fakeSequence()->push($fixtureJson)->push(FetchLeagueResponse::json());

        Passport::actingAs($user = UserFactory::new()->create());

        $this->getTestResponse(215662, $this->prediction())->assertCreated();

        $this->assertDatabaseHas('football_predictions', [
            'fixture_id' => 215662,
            'user_id'    => $user->id,
        ]);
    }

    /**
     * @dataProvider requestData
     */
    public function test_user_cannot_predict_same_fixture_more_than_once(string $fixtureJson): void
    {
        Http::fakeSequence()->push($fixtureJson)->push(FetchLeagueResponse::json());

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse(215662, $this->prediction())->assertCreated();
        $this->getTestResponse(215662, $this->prediction())->assertStatus(Response::HTTP_CONFLICT);
    }

    /**
     * @dataProvider requestData
     */
    public function test_prediction_must_be_valid_type(string $fixtureJson): void
    {
        Http::fakeSequence()->push($fixtureJson)->push(FetchLeagueResponse::json());

        Passport::actingAs(UserFactory::new()->create());

        $this->getTestResponse(215662, 'foo')->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_can_predict_only_a_fixture_that_is_not_started(): void
    {
        Http::fakeSequence()->push(FetchFixtureResponse::json())->push(FetchLeagueResponse::json());

        Passport::actingAs($user = UserFactory::new()->create());

        $this->getTestResponse(215662, $this->prediction())->assertForbidden();

        $this->assertDatabaseMissing('football_predictions', [
            'fixture_id'    => 215662,
            'user_id'       => $user->id,
        ]);
    }

    /**
     * @dataProvider requestData
     */
    public function test_fetch_fixture_predictions_will_return_expected_result_after_fixture_is_predicted(string $fixtureJson): void
    {
        \Module\Football\Prediction\Models\Prediction::truncate();

        $users = UserFactory::new()->count(10)->create();

        $fixturePredictionsResponse = function () {
            Http::fakeSequence()->push(FetchFixtureResponse::json())->push(FetchLeagueResponse::json());

            return $this->getJson(route(RouteName::FIXTURE_PREDICTIONS, ['id' => $this->hashId(215662)]));
        };

        $predictFixture = function ($user) use ($fixtureJson) {
            Http::fakeSequence()->push($fixtureJson)->push(FetchLeagueResponse::json());

            Passport::actingAs($user);
            $this->getTestResponse(215662, $this->prediction())->assertCreated();
        };

        $predictFixture($users->shift());

        $this->assertEquals($fixturePredictionsResponse()->json('data.total'), 1);

        foreach ($users as $user) {
            $predictFixture($user);
        }

        $this->assertEquals($fixturePredictionsResponse()->json('data.total'), 10);
    }

    public function requestData(): array
    {
        $json = json_decode(FetchFixtureResponse::json(), true);

        Arr::set($json, 'response.0.fixture.status.long', 'Not Started');
        Arr::set($json, 'response.0.fixture.status.short', 'NS');
        Arr::set($json, 'response.0.fixture.status.elapsed', null);
        Arr::set($json, 'response.0.fixture.goals.home', null);
        Arr::set($json, 'response.0.fixture.goals.away', null);
        Arr::set($json, 'response.0.fixture.periods.first', null);
        Arr::set($json, 'response.0.fixture.periods.second', null);
        Arr::set($json, 'response.0.fixture.score.halftime.home', null);
        Arr::set($json, 'response.0.fixture.score.halftime.away', null);
        Arr::set($json, 'response.0.fixture.score.fulltime.away', null);
        Arr::set($json, 'response.0.fixture.score.fulltime.away', null);

        return [
            [json_encode($json)],
        ];
    }

    private function prediction(): string
    {
        $predictions = array_values(PredictionPredictFixtureRequest::VALID_PREDICTIONS);

        shuffle($predictions);

        return $predictions[0];
    }
}
