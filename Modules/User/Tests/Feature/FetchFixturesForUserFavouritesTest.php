<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Clients\ApiSports\V3\Jobs\StoreTodaysFixtures;
use Module\Football\Favourites\Services\AddTeamToFavouritesService;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureByDateResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;
use Module\Football\ValueObjects\TeamId;
use Module\User\ValueObjects\UserId;

class FetchFixturesForUserFavouritesTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake(fn() => Http::response(FetchFixtureByDateResponse::json()));
         StoreTodaysFixtures::dispatch();
         Http::clearResolvedInstances();
    }

    private function getTestRespone(): TestResponse
    {
        return $this->getJson(route(RouteName::USER_FAVOURITES_FIXTURES));
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchTeamResponse::json())
            ->push(FetchFixtureResponse::json());

        $user = UserFactory::new()->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        /** @var AddTeamToFavouritesService */
        $service = app(AddTeamToFavouritesService::class);

        $service->create(new TeamId(2325), UserId::fromAuthUser());

        $this->withoutExceptionHandling()
            ->getTestRespone()
            ->assertSuccessful()
            ->assertJsonCount(1, 'data');
    }

    public function test_unauthorized_user_cannot_access_route(): void
    {
        $this->getTestRespone()->assertUnauthorized();
    }
}
