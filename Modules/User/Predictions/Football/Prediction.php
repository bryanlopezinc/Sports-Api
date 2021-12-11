<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

final class Prediction
{
    public const HOME_WIN  = 'homeWins';
    public const AWAY_WIN  = 'awayWins';
    public const DRAW      = 'draw';

    public function __construct(public string $prediction)
    {
        if (notInArray($prediction, [self::AWAY_WIN, self::DRAW, self::HOME_WIN])) {
            throw new \InvalidArgumentException('Invalid prediction type ' . $prediction);
        }
    }

    public function prediction(): string
    {
        return $this->prediction;
    }
}
