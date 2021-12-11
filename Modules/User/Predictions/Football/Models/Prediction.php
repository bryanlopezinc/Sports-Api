<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Models;

use Illuminate\Database\Eloquent\Model;

final class Prediction extends Model
{
    const UPDATED_AT = null;

    protected $table = 'football_predictions';

    protected $guarded = [];

    public $timestamps = false;
}
