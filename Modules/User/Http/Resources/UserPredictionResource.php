<?php

declare(strict_types=1);

namespace Module\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Prediction\UserPrediction;
use Module\Football\Routes\FetchFixtureRoute;

final class UserPredictionResource extends JsonResource
{
    public function __construct(private UserPrediction $prediction)
    {
        parent::__construct($prediction);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'  => 'football_prediction',
            'prediction_type' => $this->convertPredictionToString(),
            'outcome'         => $this->convertPredictionOutcomeToString(),
            'links'           => [
                'fixture' => new FetchFixtureRoute($this->prediction->fixtureId),
            ]
        ];
    }

    private function convertPredictionToString(): string
    {
        $prediction = $this->prediction->prediction;

        return match (true) {
            $prediction->isAwayToWin() => 'away_win',
            $prediction->isHomeToWin() => 'home_win',
            $prediction->isDraw()      => 'draw'
        };
    }

    private function convertPredictionOutcomeToString(): string
    {
        $outcome = $this->prediction->outCome;

        return match (true) {
            $outcome->isCorrect()   => 'won',
            $outcome->isInCorrect() => 'lost',
            $outcome->isVoid()      => 'void'
        };
    }
}
