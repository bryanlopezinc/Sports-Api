<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

use App\Utils\PaginationData;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Prediction\Prediction;
use Module\Football\Prediction\Models\PredictionCode;
use Module\Football\Prediction\Models\Prediction as PredictionModel;
use Module\Football\ValueObjects\FixtureStatus;

final class FetchUserPredictionsRepository
{
    /**
     * @return Paginator<UserPrediction>
     */
    public function fetchUserPredictions(UserId $userId, PaginationData $pagination): Paginator
    {
        $finishedFixtureStatus = $this->getfinishedFixtureStatus();

        $codes = [PredictionCode::DRAW, PredictionCode::HOME_WIN, PredictionCode::AWAY_WIN];

        $ifStatements = <<<SQL
                CASE
                   -- if the fixture was completed, winner id is null and user predicted draw then the prediction was correct.
                   WHEN
                      winner_id = null
                      AND status IN($finishedFixtureStatus)
                      AND code_id = (SELECT id FROM football_prediction_codes WHERE code = "$codes[0]")
                      THEN "correct"
                   WHEN
                      winner_id = home_team_id
                      AND code_id = (SELECT id FROM football_prediction_codes WHERE code = "$codes[1]")
                      THEN "correct"
                   WHEN
                      winner_id = away_team_id
                      AND code_id = (SELECT id FROM football_prediction_codes WHERE code = "$codes[2]")
                      THEN "correct"
                   -- if the fixture was not completed then the prediction is void.
                   WHEN
                      status NOT IN($finishedFixtureStatus)
                      THEN "void"
                   ELSE "incorrect"
                END as "outcome"
            SQL;

        /** @var Paginator */
        $result = PredictionModel::select('code', 'football_predictions.fixture_id', DB::raw($ifStatements))
            ->join('football_prediction_codes', 'code_id', '=', 'football_prediction_codes.id')
            ->join('football_fixtures_results', 'football_predictions.fixture_id', '=', 'football_fixtures_results.fixture_id')
            ->where('user_id', $userId->toInt())
            ->simplePaginate($pagination->getPerPage(), page: $pagination->getPage());

        return $result->setCollection($result->getCollection()->map($this->mapQueryResult()));
    }

    private function mapQueryResult(): \Closure
    {
        return function (PredictionModel $result) {
            return new UserPrediction(
                new FixtureId($result['fixture_id']),
                Prediction::fromCode($result['code']),
                PredictionOutcome::tryFromQueryResult($result['outcome'])
            );
        };
    }

    private function getfinishedFixtureStatus(): string
    {
        return implode(',', [FixtureStatus::FULL_TIME, FixtureStatus::FINISHED_AFTER_EXTRA_TIME, FixtureStatus::FINISHED_AFTER_PENALTY]);
    }
}
