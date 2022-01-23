<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Models;

use Illuminate\Database\Eloquent\Model;

final class Favourite extends Model
{
    const UPDATED_AT = null;
    const TEAM_TYPE = 'T';
    const LEAGUE_TYPE = 'L';

    protected $table = 'users_favourites_football';
    protected $guarded = [];
}
