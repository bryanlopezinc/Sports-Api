<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Illuminate\Support\Facades\DB;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\Prediction;
use Module\User\Predictions\Football\Models\PredictionCode;
use Module\User\Predictions\Football\Models\Prediction as PredictionModel;
use Module\User\Predictions\Football\Contracts\StoreUserPredictionRepositoryInterface;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;

final class PredictionsRepository implements StoreUserPredictionRepositoryInterface, FetchFixturePredictionsRepositoryInterface
{
    public function create(FixtureId $fixtureId, UserId $userId, Prediction $prediction): bool
    {
        $code = $this->predictionTypeMap()[$prediction->prediction()];

        return DB::statement(
            "INSERT INTO football_predictions (fixture_id, user_id, code_id) VALUES (?, ?, (SELECT id FROM football_prediction_codes WHERE code = ?))",
            [$fixtureId->toInt(), $userId->toInt(), $code]
        );
    }

    public function userHasPredictedFixture(UserId $userId, FixtureId $fixtureId): bool
    {
        return PredictionModel::where([
            'fixture_id' => $fixtureId->toInt(),
            'user_id'    => $userId->toInt(),
        ])->exists();
    }

    public function fetchUserPrediction(FixtureId $fixtureId, UserId $userId): Prediction
    {
        $result = PredictionModel::select('code')
            ->join('football_prediction_codes', 'code_id', '=', 'football_prediction_codes.id')
            ->where('fixture_id', $fixtureId->toInt())
            ->where('user_id', $userId->toInt())
            ->first();

        $prediction =  array_flip($this->predictionTypeMap())[$result->code];

        return new Prediction($prediction);
    }

    /**
     * Map of prediction type and corresponding predictionCode name
     *
     * @return array<string, string>
     */
    private function predictionTypeMap(): array
    {
        return [
            Prediction::AWAY_WIN   => PredictionCode::AWAY_WIN,
            Prediction::HOME_WIN   => PredictionCode::HOME_WIN,
            Prediction::DRAW       => PredictionCode::DRAW
        ];
    }

    public function fetchPredictionsResultFor(FixtureId $fixtureId): FixturePredictionsResult
    {
        $query = <<<"SQL"
                (SELECT COUNT(id) FROM football_predictions WHERE code_id = (SELECT id FROM football_prediction_codes WHERE code = ?) AND fixture_id = {$fixtureId->toInt()}) AS home_wins,
                (SELECT COUNT(id) FROM football_predictions WHERE code_id = (SELECT id FROM football_prediction_codes WHERE code = ?) AND fixture_id = {$fixtureId->toInt()}) AS away_wins,
                (SELECT COUNT(id) FROM football_predictions WHERE code_id = (SELECT id FROM football_prediction_codes WHERE code = ?) AND fixture_id = {$fixtureId->toInt()}) AS draws,
                (SELECT COUNT(*) FROM football_predictions WHERE fixture_id = {$fixtureId->toInt()}) AS total
        SQL;

        $bindings = [PredictionCode::HOME_WIN, PredictionCode::AWAY_WIN, PredictionCode::DRAW];

        $predictions = PredictionModel::selectRaw($query, $bindings)->first();

        if ($predictions === null) {
            return new FixturePredictionsResult(0, 0, 0, 0);
        }

        return new FixturePredictionsResult(
            $predictions['home_wins'],
            $predictions['away_wins'],
            $predictions['draws'],
            $predictions['total']
        );
    }
}
