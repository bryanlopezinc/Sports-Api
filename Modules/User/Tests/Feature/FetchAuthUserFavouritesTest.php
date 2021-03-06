<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Favourites\AddLeagueRoute;
use Module\Football\Favourites\AddTeamRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;

class FetchAuthUserFavouritesTest extends TestCase
{
    private function getTestRespone(): TestResponse
    {
        return $this->getJson(route(RouteName::AUTH_USER_FAVOURITES));
    }

    public function test_success_response(): void
    {
        Http::fakeSequence()
            ->push(FetchTeamResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchTeamResponse::json());

        Passport::actingAs(UserFactory::new()->create()); // @phpstan-ignore-line

        $this->postJson(
            (new AddTeamRoute(TeamFactory::new()->toDto()->getId()))->toString()
        );

        $this->postJson(
            (new AddLeagueRoute(LeagueFactory::new()->toDto()->getId()))->toString()
        );

        $this->withoutExceptionHandling()->getTestRespone()->assertSuccessful();
    }

    public function test_can_view_own_favourites_when_profile_is_private(): void
    {
        Http::fakeSequence()
            ->push(FetchTeamResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchLeagueResponse::json())
            ->push(FetchTeamResponse::json());

        Passport::actingAs(UserFactory::new()->create()); // @phpstan-ignore-line

        $this->postJson(
            (new AddTeamRoute(TeamFactory::new()->toDto()->getId()))->toString()
        );

        $this->postJson(
            (new AddLeagueRoute(LeagueFactory::new()->toDto()->getId()))->toString()
        );

        $this->withoutExceptionHandling()->getTestRespone()->assertSuccessful();
    }

    public function test_unauthorized_user_cannot_access_route(): void
    {
        $this->getTestRespone()->assertUnauthorized();
    }
}
