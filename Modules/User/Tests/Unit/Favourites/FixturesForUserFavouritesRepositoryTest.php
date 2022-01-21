<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Favourites;

use App\ValueObjects\Date;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Module\Football\Clients\ApiSports\V3\FetchFixturesByDateHttpClient;
use Module\Football\Clients\ApiSports\V3\Jobs\StoreTodaysFixtures;
use Module\Football\Services\FetchFixtureService;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureByDateResponse;
use Module\Football\ValueObjects\LeagueId;
use Tests\TestCase;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\TeamId;
use Module\User\Favourites\Football\FavouritesRepository;
use Module\User\Favourites\FixturesForUserFavouritesRepository;

class FixturesForUserFavouritesRepositoryTest extends TestCase
{
    //use LazilyRefreshDatabase;

    private FavouritesRepository $repository;
    private FixturesForUserFavouritesRepository $fixturesRepository;
    private Date $date;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(FavouritesRepository::class);
        $this->fixturesRepository = new FixturesForUserFavouritesRepository(app(FetchFixtureService::class));
        $this->date = new Date(today()->toDateString());

        DB::table('football_fixtures')->truncate();
        DB::table('users_favourites')->truncate();

        Http::fake(fn () => Http::response(FetchFixtureByDateResponse::json()));
        
        (new StoreTodaysFixtures)->handle(new FetchFixturesByDateHttpClient());
    }

    public function test_will_return_empty_array_when_favourites_does_not_have_any_fixture(): void
    {
        $userId = new UserId(300);

        //no team n league with id 1111 in stub
        $this->repository->addTeam(new TeamId(1111), $userId);
        $this->repository->addLeague(new LeagueId(1111), $userId);

        $this->assertEmpty($this->fixturesRepository->getFixtureIds($userId, $this->date));
    }

    public function test_will_return_correct_fixture_ids_for_team_and_league(): void
    {
        $userId = new UserId(300);

        //a valid team/league id in stub
        $this->repository->addTeam(new TeamId(2325), $userId);
        $this->repository->addLeague(new LeagueId(253), $userId);

        $this->assertCount(8, $result = $this->fixturesRepository->getFixtureIds($userId, $this->date));
        $this->assertEquals($result,  [688256, 695533, 695534, 695535, 695536, 695537, 695538, 695539]);
    }

    public function test_will_return_correct_fixture_ids_when_both_team_and_league_has_fixtures(): void
    {
        $userId = new UserId(300);

        //any valid team (in json stub) in a league with id 233
        $this->repository->addTeam(new TeamId(1616), $userId);
        $this->repository->addLeague(new LeagueId(253), $userId);

        $this->assertCount(7, $result = $this->fixturesRepository->getFixtureIds($userId, $this->date));
        $this->assertEquals($result,  [695533, 695534, 695535, 695536, 695537, 695538, 695539]);
    }

    public function test_will_return_correct_fixture_ids_when_a_team_has_fixture(): void
    {
        $userId = new UserId(300);

        $this->repository->addTeam(new TeamId(463), $userId);

        $this->assertCount(1, $result = $this->fixturesRepository->getFixtureIds($userId, $this->date));
        $this->assertEquals($result,  [720399]);
    }

    public function test_will_return_correct_fixture_ids_when_both_teams_has_fixture(): void
    {
        $userId = new UserId(300);

        //any home and away team in same fixture
        $this->repository->addTeam(new TeamId(1616), $userId);
        $this->repository->addTeam(new TeamId(1957), $userId);

        $this->assertCount(1, $result = $this->fixturesRepository->getFixtureIds($userId, $this->date));
        $this->assertEquals($result,  [695533]);
    }
}
