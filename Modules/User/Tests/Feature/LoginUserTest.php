<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\TestResponse;
use Module\User\Routes\RouteName;
use Module\User\Factories\UserFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Database\Factories\ClientFactory;

class LoginUserTest extends TestCase
{
    private function getTestRespone(array $query): TestResponse
    {
        return $this->postJson(route(RouteName::LOGIN), $query);
    }

    public function test_success_response(): void
    {
        $client = ClientFactory::new()->asPasswordClient()->create();

        $this->getTestRespone([
            'username'      => UserFactory::new()->create()->username, // @phpstan-ignore-line
            'password'      => 'password',
            'grant_type'    => 'password',
            'client_id'     => $client->id, // @phpstan-ignore-line
            'client_secret' => $client->secret, // @phpstan-ignore-line
        ])
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $assert) {
                $assert->where('token_type', 'Bearer');
                $assert->has('expires_in');
                $assert->has('access_token');
                $assert->has('refresh_token');
            });
    }
}
