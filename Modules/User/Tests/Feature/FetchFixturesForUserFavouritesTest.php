<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\Factories\TeamFactory;
use Module\Football\Favourites\AddTeamRoute;
use Module\User\Factories\UserFactory;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;

class FetchFixturesForUserFavouritesTest extends TestCase
{
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

        $this->postJson(
            (new AddTeamRoute(TeamFactory::new()->toDto()->getId()))->toString()
        );

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
