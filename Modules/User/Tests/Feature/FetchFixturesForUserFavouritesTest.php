<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\ValueObjects\TeamId;
use Module\User\Favourites\Football\FavouritesRepository;
use Module\User\ValueObjects\UserId;

class FetchFixturesForUserFavouritesTest extends TestCase
{
    private function getTestRespone(): TestResponse
    {
        return $this->getJson(route(RouteName::USER_FAVOURITES_FIXTURES));
    }

    public function test_success_response(): void
    {
        Http::fake(Http::response(FetchFixtureResponse::json()));

        $user = UserFactory::new()->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        /** @var FavouritesRepository */
        $repository = app(FavouritesRepository::class);

        $repository->addTeam(new TeamId(463), UserId::fromAuthUser());

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
