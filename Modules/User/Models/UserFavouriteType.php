<?php

declare(strict_types=1);

namespace Module\User\Models;

use Illuminate\Database\Eloquent\Model;

final class UserFavouriteType extends Model
{
    public const TEAM_TYPE   = 'team';
    public const LEAGUE_TYPE = 'league';

    //sports types
    public const SPORTS_TYPE_FOOTBALL = 'football';

    protected $table = 'user_favourite_type';
    public $timestamps = false;
}
