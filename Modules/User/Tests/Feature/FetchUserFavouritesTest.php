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
use Module\Football\Favourites\AddTeamRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;

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
        Http::fakeSequence()->push(FetchTeamResponse::json());

        $this->postJson(
            (new AddTeamRoute(TeamFactory::new()->toDto()->getId()))->toString()
        );

        $user = UserFactory::new()->create();

        $this->withoutExceptionHandling()
            ->getTestResponse($user->id) // @phpstan-ignore-line
            ->assertSuccessful();
    }

    public function test_authorized_user_can_view_favourites(): void
    {
        Http::fakeSequence()->push(FetchTeamResponse::json());

        $this->postJson(
            (new AddTeamRoute(TeamFactory::new()->toDto()->getId()))->toString()
        );

        $user = UserFactory::new()->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $this->withoutExceptionHandling()
            ->getTestResponse($user->id) // @phpstan-ignore-line
            ->assertSuccessful();
    }

    public function test_authorized_user_cannot_view_user_favourites_when_user_profile_is_private(): void
    {
        Passport::actingAs(UserFactory::new()->create()); // @phpstan-ignore-line

        $this->getTestResponse(UserFactory::new()->private()->create()->id)->assertForbidden();
    }

    public function test_unauthorized_user_cannot_view_user_favourites_when_user_profile_is_private(): void
    {
        $this->getTestResponse(UserFactory::new()->private()->create()->id)->assertForbidden();
    }
}
