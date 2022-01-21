<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

use Module\Football\Prediction\Models\PredictionCode;

enum Prediction
{
    case HOME_WIN;
    case AWAY_WIN;
    case DRAW;

    public function isDraw(): bool
    {
        return $this == self::DRAW;
    }

    public function isHomeToWin(): bool
    {
        return $this == self::HOME_WIN;
    }

    public function isAwayToWin(): bool
    {
        return $this == self::AWAY_WIN;
    }

    public static function fromCode(string $code): self
    {
        return match ($code) {
            PredictionCode::AWAY_WIN => self::AWAY_WIN,
            PredictionCode::HOME_WIN => self::HOME_WIN,
            PredictionCode::DRAW     => self::DRAW
        };
    }

    public static function fromRequest(PredictFixtureRequest $request, string $key): self
    {
        return match ($request->input($key)) {
            $request::VALID_PREDICTIONS['1W'] => self::HOME_WIN,
            $request::VALID_PREDICTIONS['2W'] => self::AWAY_WIN,
            $request::VALID_PREDICTIONS['D']  => self::DRAW,
        };
    }

    public function toCode(): string
    {
        return match (true) {
            $this->isAwayToWin() => PredictionCode::AWAY_WIN,
            $this->isHomeToWin() => PredictionCode::HOME_WIN,
            $this->isDraw()      => PredictionCode::DRAW
        };
    }
}
