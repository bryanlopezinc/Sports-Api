<?php

declare(strict_types=1);

namespace Module\Football\Prediction\Models;

use Illuminate\Database\Eloquent\Model;

final class Prediction extends Model
{
    const UPDATED_AT = null;

    protected $table = 'football_predictions';

    protected $guarded = [];

    public $timestamps = false;
}
