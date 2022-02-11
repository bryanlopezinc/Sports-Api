<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

enum PredictionOutcome: string
{
    case CORRECT     = 'correct';
    case INCORRECT   = 'inCorrect';
    case VOID        = 'void'; // fixture did not start for various reasons or fixture was abandoned

    public static function tryFromQueryResult(string $outcome): self
    {
        return match ($outcome) {
            'correct'   => self::CORRECT,
            'void'      => self::VOID,
            'incorrect' => self::INCORRECT,
        };
    }

    public function isCorrect(): bool
    {
        return $this->value === self::CORRECT->value;
    }

    public function isInCorrect(): bool
    {
        return $this->value === self::INCORRECT->value;
    }

    public function isVoid(): bool
    {
        return $this->value === self::VOID->value;
    }
}
