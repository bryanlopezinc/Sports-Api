<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Prediction;

use App\Utils\PaginationData;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Module\Football\Clients\ApiSports\V3\Jobs\StoreFixturesResult;
use Module\Football\Prediction\FetchUserPredictionsRepository;
use Tests\TestCase;
use Module\User\ValueObjects\UserId;
use Module\User\Factories\UserFactory;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\Prediction;
use Module\Football\Prediction\PredictionsRepository;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueFixturesByDateResponse;
use Module\Football\ValueObjects\FixtureStatus;

class FetchUserPredictionsRepositoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    private PredictionsRepository $repository;
    private FetchUserPredictionsRepository $predictionsRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PredictionsRepository;
        $this->predictionsRepository = new FetchUserPredictionsRepository;

        Http::fake(fn () => Http::response(FetchLeagueFixturesByDateResponse::json()));

        dispatch(new StoreFixturesResult);
    }

    public function test_fetch_user_predictions_will_return_correct_when_user_predicts_right(): void
    {
        $userId = new UserId(UserFactory::new()->create()->id);

        $this->repository->create(new FixtureId(710638), $userId, Prediction::AWAY_WIN);
        $this->assertTrue($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData())[0]->outCome->isCorrect());

        $this->repository->create(new FixtureId(710639), $userId, Prediction::HOME_WIN);
        $this->assertTrue($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData())[0]->outCome->isCorrect());

        $this->repository->create(new FixtureId(710640), $userId, Prediction::DRAW);
        $this->assertTrue($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData())[0]->outCome->isCorrect());
    }

    public function test_fetch_user_predictions_will_return_incorrect_when_user_predicts_wrong(): void
    {
        $userId = new UserId(UserFactory::new()->create()->id);

        $this->repository->create(new FixtureId(710638), $userId, Prediction::HOME_WIN);
        $this->assertTrue($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData())[0]->outCome->isInCorrect());

        $this->repository->create(new FixtureId(710639), $userId, Prediction::AWAY_WIN);
        $this->assertTrue($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData())[0]->outCome->isInCorrect());

        $this->repository->create(new FixtureId(710640), $userId, Prediction::HOME_WIN);
        $this->assertTrue($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData())[0]->outCome->isInCorrect());
    }

    public function test_fetch_user_predictions_will_return_empty_when_fixture_result_is_not_yet_recorded(): void
    {
        $userId = new UserId(UserFactory::new()->create()->id);

        $this->repository->create(new FixtureId(20), $userId, Prediction::DRAW); //any fixture id not in json stub
        $this->assertEmpty($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData()));
    }

    public function test_fetch_user_predictions_will_return_void_when_fixture_was_not_concluded(): void
    {
        DB::table('football_fixtures_results')->insert([
            'fixture_id'   => 9001,
            'home_team_id' => 100,
            'away_team_id' => 300,
            'status'       => FixtureStatus::CANCELLED,
            'winner_id'    => null
        ]);

        $userId = new UserId(UserFactory::new()->create()->id);

        $this->repository->create(new FixtureId(9001), $userId, Prediction::DRAW);
        $this->assertTrue($this->predictionsRepository->fetchUserPredictions($userId, new PaginationData())[0]->outCome->isVoid());
    }
}
