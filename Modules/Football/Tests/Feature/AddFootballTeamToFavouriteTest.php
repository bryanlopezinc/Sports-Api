<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\User\Factories\UserFactory;
use Module\Football\Factories\TeamFactory;
use Module\Football\Favourites\AddTeamRoute;
use Module\Football\Favourites\Models\Favourite;
use Module\User\Favourites\Models\FavouriteCount;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;
use Module\Football\ValueObjects\TeamId;

class AddFootballTeamToFavouriteTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function getTestRespone(int $id): TestResponse
    {
        return $this->postJson(
            (new AddTeamRoute(new TeamId($id)))->toString()
        );
    }

    public function test_returns_409_status_code_when_favourite_already_exists(): void
    {
        Http::fake(fn () => Http::response(FetchTeamResponse::json()));

        Passport::actingAs(UserFactory::new()->create()); // @phpstan-ignore-line

        $this->getTestRespone(200)->assertSuccessful();
        $this->getTestRespone(200)->assertStatus(409);
    }

    public function test_returns_404_status_code_when_team_does_not_exists(): void
    {
        Http::fake(fn () => Http::response([], 404));

        $user = UserFactory::new()->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $this->getTestRespone(900)->assertNotFound();

        $this->assertDatabaseMissing((new Favourite())->getTable(),
            [
                'user_id'      => $user->id, // @phpstan-ignore-line
                'favourite_id' => 900,
                'type'         => Favourite::TEAM_TYPE
            ]
        );
    }

    public function test_success_response(): void
    {
        Http::fake(fn () => Http::response(FetchTeamResponse::json()));

        $user = UserFactory::new()->create();

        Passport::actingAs($user); // @phpstan-ignore-line

        $teamId = TeamFactory::new()->toDto()->getId();

        $this->withoutExceptionHandling()
            ->getTestRespone($teamId->toInt())
            ->assertCreated();

        $this->assertDatabaseHas((new Favourite)->getTable(),
            [
                'user_id'      => $user->id, // @phpstan-ignore-line
                'favourite_id' => $teamId->toInt(),
                'type'         => Favourite::TEAM_TYPE
            ]
        );

        $this->assertDatabaseHas((new FavouriteCount())->getTable(),
            [
                'user_id' => $user->id, // @phpstan-ignore-line
                'count'   => 1
            ]
        );
    }

    public function test_unauthorized_user_cannot_add_favourite(): void
    {
        $this->getTestRespone(22)->assertUnauthorized();
    }
}
