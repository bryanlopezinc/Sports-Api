<?php

declare(strict_types=1);

namespace Module\User\Favourites\Models;

use Illuminate\Database\Eloquent\Model;

final class FavouriteType extends Model
{
    public const TEAM_TYPE   = 'team';
    public const LEAGUE_TYPE = 'league';

    //sports types
    public const SPORTS_TYPE_FOOTBALL = 'football';

    protected $table = 'user_favourite_type';
    
    public $timestamps = false;
}
