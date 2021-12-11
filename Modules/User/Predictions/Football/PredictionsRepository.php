<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Module\User\ValueObjects\UserId;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\Prediction;
use Module\User\Predictions\Football\Models\PredictionCode;
use Module\User\Exceptions\DuplicatePredictionEntryException;
use Module\User\Predictions\Football\Models\Prediction as PredictionModel;
use Module\User\Predictions\Football\Contracts\StoreUserPredictionRepositoryInterface;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;

final class PredictionsRepository implements StoreUserPredictionRepositoryInterface, FetchFixturePredictionsRepositoryInterface
{
    public function create(FixtureId $fixtureId, UserId $userId, Prediction $prediction): bool
    {
        try {
            PredictionModel::create([
                'fixture_id'    => $fixtureId->toInt(),
                'user_id'       => $userId->toInt(),
                'code_id'       => $this->getPredictionCodeIdFrom($prediction),
                'predicted_on'  => now()
            ]);

            return true;
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                throw new DuplicatePredictionEntryException();
            }

            throw $exception;
        }
    }

    private function getPredictionCodeIdFrom(Prediction $prediction): int
    {
        $lookUp = [
            $prediction::AWAY_WIN   => PredictionCode::AWAY_WIN,
            $prediction::HOME_WIN   => PredictionCode::HOME_WIN,
            $prediction::DRAW       => PredictionCode::DRAW
        ];

        return PredictionCode::where('code', $lookUp[$prediction->prediction()])->first()->id;
    }

    public function fetchPredictionsTotalsFor(FixtureId $fixtureId): FixturePredictionsTotals
    {
        $code = PredictionCode::all();

        $expression = "COUNT(id) FROM football_predictions WHERE code_id=? and fixture_id=?";

        $codeId = fn (string $codeName): int => $code->where('code', $codeName)->first()->id;

        $predictions = PredictionModel::selectSub(fn (Builder $builder) => $builder->selectRaw($expression, [$codeId(PredictionCode::HOME_WIN), $fixtureId->toInt()]), 'home_wins')
            ->selectSub(fn (Builder $builder) => $builder->selectRaw($expression, [$codeId(PredictionCode::AWAY_WIN), $fixtureId->toInt()]), 'away_wins')
            ->selectSub(fn (Builder $builder) => $builder->selectRaw($expression, [$codeId(PredictionCode::DRAW), $fixtureId->toInt()]), 'draws')
            ->selectSub(fn (Builder $builder) => $builder->selectRaw('COUNT(*) FROM football_predictions WHERE fixture_id=?', [$fixtureId->toInt()]), 'total')
            ->first();

        if ($predictions ===  null) {
            return new FixturePredictionsTotals(0, 0, 0, 0);
        }

        return new FixturePredictionsTotals(
            $predictions['home_wins'],
            $predictions['away_wins'],
            $predictions['draws'],
            $predictions['total']
        );
    }
}
