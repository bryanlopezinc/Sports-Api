<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Models;

use Illuminate\Database\Eloquent\Model;

final class PredictionCode extends Model
{
    public const HOME_WIN  = '1W';
    public const AWAY_WIN  = '2W';
    public const DRAW      = 'D';

    protected $table = 'football_prediction_codes';

    public $timestamps = false;

    protected $guarded = [];
}
