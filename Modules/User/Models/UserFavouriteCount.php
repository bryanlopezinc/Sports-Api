<?php

declare(strict_types=1);

namespace Module\User\Models;

use Illuminate\Database\Eloquent\Model;

final class UserFavouriteCount extends Model
{
    protected $table = 'users_favourites_count';
    protected $guarded = [];
}