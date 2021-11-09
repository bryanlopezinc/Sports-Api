<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Module\User\Routes\RouteName;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Illuminate\Testing\Fluent\AssertableJson;

class FetchAuthUserProfileTest extends TestCase
{
    private function getTestRespone(): TestResponse
    {
        return $this->getJson(route(RouteName::AUTH_USER_PROFILE));
    }

    public function test_can_view_own_profile_when_profile_is_private(): void
    {
        $user = UserFactory::new()->private()->create();

        Passport::actingAs($user);

        $this->getTestRespone()->assertSuccessful();
    }

    public function test_unauthorized_user_cannot_access_route(): void
    {
        $this->getTestRespone()->assertUnauthorized();
    }

    public function test_success_response(): void
    {
        Passport::actingAs(UserFactory::new()->create()); // @phpstan-ignore-line

        $this->getTestRespone()
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $assert) {
                $assert->where('data.links.favourites', route(RouteName::AUTH_USER_FAVOURITES));
                $assert->etc();
            });
    }
}
