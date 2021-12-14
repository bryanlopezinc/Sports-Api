<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Module\User\Favourites\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Factories\TeamFactory;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;

class FetchUserFavouritesTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(route(RouteName::FAVOURITES, [
            'id' => $id
        ]));
    }

    public function test_unauthorized_user_can_view_favourites(): void
    {
        Http::fakeSequence()
            ->push(FetchTeamResponse::json())
            ->push(FetchLeagueResponse::json());

        $league = LeagueFactory::new()->toDto();
        $team = TeamFactory::new()->toDto();

        $user = UserFactory::new()
            ->has(Factory::new()->footballLeagueType()->favouriteId($league->getId()->toInt()), 'favourites')
            ->has(Factory::new()->favouriteId($team->getId()->toInt()), 'favourites')
            ->create();

        $this->withoutExceptionHandling()
            ->getTestResponse($user->id) // @phpstan-ignore-line
            ->assertSuccessful();
    }

    public function test_authorized_user_can_view_favourites(): void
    {
        Http::fakeSequence()
            ->push(FetchTeamResponse::json())
            ->push(FetchLeagueResponse::json());

        $league = LeagueFactory::new()->toDto();
        $team = TeamFactory::new()->toDto();

        $user = UserFactory::new()
            ->has(Factory::new()->footballLeagueType()->favouriteId($league->getId()->toInt()), 'favourites')
            ->has(Factory::new()->favouriteId($team->getId()->toInt()), 'favourites')
            ->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $this->withoutExceptionHandling()
            ->getTestResponse($user->id) // @phpstan-ignore-line
            ->assertSuccessful();
    }

    public function test_authorized_user_cannot_view_user_favourites_when_user_profile_is_private(): void
    {
        Passport::actingAs(UserFactory::new()->create()); // @phpstan-ignore-line

        $this->getTestResponse(UserFactory::new()->private()->create()->id)
            ->assertForbidden();
    }

    public function test_unauthorized_user_cannot_view_user_favourites_when_user_profile_is_private(): void
    {
        $this->getTestResponse(UserFactory::new()->private()->create()->id)
            ->assertForbidden();
    }
}
