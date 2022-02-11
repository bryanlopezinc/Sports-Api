<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Clients\ApiSports\V3\Jobs\StoreFixturesResult;
use Module\Football\Prediction\PredictionsRepository;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueFixturesByDateResponse;
use Module\Football\ValueObjects\FixtureId;
use Module\User\ValueObjects\UserId;
use Module\Football\Prediction\Prediction;

class FetchUserPredictionsTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake(fn () => Http::response(FetchLeagueFixturesByDateResponse::json()));
        dispatch(new StoreFixturesResult);
    }

    private function createPredictionForUser(int $id): void
    {
        $predictions = [Prediction::HOME_WIN, prediction::AWAY_WIN, Prediction::DRAW];

        shuffle($predictions);

        //fixture id should be any id in json stub
        (new PredictionsRepository)->create(new FixtureId(710638), new UserId($id), $predictions[0]);
    }

    private function getTestRespone(array $query = []): TestResponse
    {
        return $this->getJson(route(RouteName::USER_PREDICtions, $query));
    }

    public function test_cannot_view_user_predictions_when_profile_is_private(): void
    {
        $user = UserFactory::new()->private()->create();

        $this->getTestRespone(['id' => $user->id])->assertForbidden();
    }

    public function test_returns_not_found_response_when_user_does_not_exists(): void
    {
        $this->getTestRespone(['id' => UserFactory::new()->create()->id + 1])->assertNotFound(); // @phpstan-ignore-line
    }

    public function test_must_have_required_attributes(): void
    {
        $this->getTestRespone()->assertJsonValidationErrorFor('id');
    }

    public function test_will_return_auth_user_predictions(): void
    {
        Passport::actingAs($user = UserFactory::new()->create());

        $this->createPredictionForUser($user->id);

        $this->getJson(route(RouteName::AUTH_USER_PREDICtions))->assertSuccessful()->assertJsonCount(1, 'data');
    }

    public function test_will_throw_unauthorized_exception_when_accessing_auth_route_with_no_access_token(): void
    {
        $this->getJson(route(RouteName::AUTH_USER_PREDICtions))->assertUnauthorized();
    }

    public function test_success_response(): void
    {
        $userId = UserFactory::new()->create()->id;

        $this->createPredictionForUser($userId);

        $this->getTestRespone(['id' => $userId])->assertSuccessful()->assertJsonCount(1, 'data');
        $this->getTestRespone(['id' => UserFactory::new()->create()->id])->assertSuccessful()->assertJsonCount(0, 'data');
    }
}
