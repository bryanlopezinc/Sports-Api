<?php

declare(strict_types=1);

namespace Module\User\Tests\Feature;

use Tests\TestCase;
use Module\User\Routes\RouteName;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Database\Factories\ClientFactory;

class CreateUserTest extends TestCase
{
    private function getTestRespone(array $query, array $headers = []): TestResponse
    {
        return $this->postJson(route(RouteName::CREATE), $query, $headers);
    }

    public function test_success_response(): void
    {
        $user = UserFactory::new()->make();

        $client = ClientFactory::new()->asPasswordClient()->create();

        $this->withoutExceptionHandling()
            ->getTestRespone([
                'email'                  => $user->email, // @phpstan-ignore-line
                'is_private'             => $user->is_private, // @phpstan-ignore-line
                'name'                   => $user->name, // @phpstan-ignore-line
                'password'               => 'password',
                'password_confirmation'  => 'password',
                'username'               => $user->username, // @phpstan-ignore-line
                'client_id'              => $client->id, // @phpstan-ignore-line
                'client_secret'          => $client->secret, // @phpstan-ignore-line
                'grant_type'             => 'password',
            ])
            ->assertCreated()
            ->assertJson(function (AssertableJson $assert) {
                $assert->where('token_type', 'Bearer');
                $assert->has('expires_in');
                $assert->has('access_token');
                $assert->has('refresh_token');
                $assert->where('data.links.favourites', route(RouteName::AUTH_USER_FAVOURITES));
                $assert->where('data.links.self', route(RouteName::AUTH_USER_PROFILE));
                $assert->where('data.attributes.favourites_count', 0);
                $assert->etc();
            });

        $this->assertDatabaseHas('users', [
            'username'  => $user->username, // @phpstan-ignore-line
            'email'     => $user->email, // @phpstan-ignore-line
        ]);
    }

    public function test_email_must_be_unique(): void
    {
        $user = UserFactory::new()->create();
        $client = ClientFactory::new()->asPasswordClient()->create();

        $this->getTestRespone([
            'email'                  => $user->email, // @phpstan-ignore-line
            'is_private'             => $user->is_private, // @phpstan-ignore-line
            'name'                   => $user->name, // @phpstan-ignore-line
            'password'               => 'password',
            'password_confirmation'  => 'password',
            'username'               => UserFactory::new()->make()->username, // @phpstan-ignore-line
            'client_id'              => $client->id, // @phpstan-ignore-line
            'client_secret'          => $client->secret, // @phpstan-ignore-line
            'grant_type'             => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors('username')
            ->assertJsonValidationErrors(['email' => 'Email is already taken']);
    }

    public function test_username_must_be_unique(): void
    {
        $user = UserFactory::new()->create();
        $client = ClientFactory::new()->asPasswordClient()->create();

        $this->getTestRespone([
            'email'                  => UserFactory::new()->make()->email, // @phpstan-ignore-line
            'is_private'             => $user->is_private, // @phpstan-ignore-line
            'name'                   => $user->name, // @phpstan-ignore-line
            'password'               => 'password',
            'password_confirmation'  => 'password',
            'username'               => $user->username, // @phpstan-ignore-line
            'client_id'              => $client->id, // @phpstan-ignore-line
            'client_secret'          => $client->secret, // @phpstan-ignore-line
            'grant_type'             => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors('email')
            ->assertJsonValidationErrors(['username' => 'Username is already taken']);
    }
}
